# Production SSL Certificate Setup

## Issue: SSL Certificate Mismatch

**Error from logs:**
```
[ssl:warn] [pid 625] AH01909: www.example.com:443:0 server certificate does NOT include an ID which matches the server name
```

This warning indicates the SSL certificate does not include Subject Alternative Names (SANs) for all the domains being served.

## Affected Domains

Talishar currently serves multiple domains:
- `talishar.net` (main domain)
- `www.talishar.net` (www subdomain)
- `api.talishar.net` (API backend)
- `fe.talishar.net` (Frontend)
- `legacy.talishar.net` (Legacy site)

## Root Cause

The current SSL certificate is either:
1. ❌ Only issued for a single domain (e.g., `www.example.com`)
2. ❌ Missing Subject Alternative Names (SANs) for all subdomains
3. ❌ Placeholder certificate not matching production domains

## Solution

### Step 1: Obtain or Renew Certificate

Use **Let's Encrypt** (recommended for open source projects - it's free):

```bash
# Install Certbot if not already installed
apt-get install certbot python3-certbot-apache

# Generate certificate for all domains (single certificate)
certbot certonly --apache \
  -d talishar.net \
  -d www.talishar.net \
  -d api.talishar.net \
  -d fe.talishar.net \
  -d legacy.talishar.net

# Follow the prompts to validate domain ownership
```

This creates certificates at:
- `/etc/letsencrypt/live/talishar.net/fullchain.pem` (certificate)
- `/etc/letsencrypt/live/talishar.net/privkey.pem` (private key)

### Step 2: Verify Certificate Includes All Domains

```bash
openssl x509 -in /etc/letsencrypt/live/talishar.net/fullchain.pem -text -noout | grep -A1 "Subject Alternative Name"
```

Expected output:
```
Subject Alternative Name: 
    DNS:talishar.net, DNS:www.talishar.net, DNS:api.talishar.net, DNS:fe.talishar.net, DNS:legacy.talishar.net
```

### Step 3: Configure Apache VirtualHosts

Create `/etc/apache2/sites-available/talishar-ssl.conf`:

```apache
# Main domain with all subdomains
<VirtualHost *:443>
    ServerName talishar.net
    ServerAlias www.talishar.net api.talishar.net fe.talishar.net legacy.talishar.net
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/talishar.net/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/talishar.net/privkey.pem
    SSLCertificateChainFile /etc/letsencrypt/live/talishar.net/chain.pem
    
    # Modern SSL configuration
    SSLProtocol TLSv1.2 TLSv1.3
    SSLCipherSuite HIGH:!aNULL:!MD5
    SSLHonorCipherOrder on
    
    # HSTS (Strict Transport Security)
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # Proxy to Docker container
    ProxyPreserveHost On
    ProxyPass / http://localhost:8080/
    ProxyPassReverse / http://localhost:8080/
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/talishar-error.log
    CustomLog ${APACHE_LOG_DIR}/talishar-access.log combined
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName talishar.net
    ServerAlias www.talishar.net api.talishar.net fe.talishar.net legacy.talishar.net
    Redirect permanent / https://talishar.net/
</VirtualHost>
```

### Step 4: Enable Apache Modules

```bash
a2enmod ssl
a2enmod proxy
a2enmod proxy_http
a2enmod headers
a2ensite talishar-ssl
a2dissite 000-default  # Disable default site if applicable

# Test configuration
apache2ctl configtest
# Should output: Syntax OK

# Reload Apache
systemctl reload apache2
```

### Step 5: Enable Auto-Renewal

Let's Encrypt certificates expire after 90 days. Auto-renew them:

```bash
# Test renewal (dry run)
certbot renew --dry-run

# Enable automatic renewal (runs daily)
systemctl enable certbot.timer
systemctl start certbot.timer

# Check renewal status
certbot renew --status
```

## Verification

### Check Certificate is Valid

```bash
# View cert details
openssl x509 -in /etc/letsencrypt/live/talishar.net/fullchain.pem -text -noout

# Check expiration
openssl x509 -in /etc/letsencrypt/live/talishar.net/fullchain.pem -noout -dates

# Verify SANs are present
openssl x509 -in /etc/letsencrypt/live/talishar.net/fullchain.pem -text -noout | grep DNS
```

### Check Apache is Serving HTTPS

```bash
# Test HTTPS on main domain
curl -I https://talishar.net

# Test HTTPS on subdomains
curl -I https://api.talishar.net
curl -I https://fe.talishar.net
```

Should return HTTP 200 without SSL warnings.

### Check Error Logs

```bash
# Monitor Apache error log
tail -f /var/log/apache2/talishar-error.log

# Should NOT see:
# [ssl:warn] AH01909: ... server certificate does NOT include an ID
```

## Docker Considerations

**Important:** This Docker container (`web-server` in docker-compose.yml) serves HTTP only (port 8080). 

The SSL/HTTPS layer is handled by:
- **Development**: Browsers don't validate SSL for `localhost`
- **Production**: Reverse proxy (Apache/Nginx on host) handles SSL termination

```
User HTTPS Request
    ↓
Host Machine (Apache) - Handles SSL/TLS
    ↓
Docker Container (HTTP, port 8080) - Serves content
```

## Troubleshooting

### Certificate Still Shows Wrong Domain

1. Clear Apache cache:
   ```bash
   systemctl restart apache2
   ```

2. Clear browser cache:
   - Open DevTools (F12) → Application → Certificates
   - Or use: `Ctrl+Shift+Delete` (Chrome/Firefox)

3. Verify with different tool:
   ```bash
   openssl s_client -connect talishar.net:443 -servername talishar.net
   ```

### Certbot Can't Validate Domain

Ensure:
- DNS records point to server IP
- Firewall allows port 80 (HTTP for validation)
- No duplicate certificate conflicts

```bash
certbot certificates  # List all certs
certbot delete --cert-name talishar.net  # Remove conflicting cert
```

### Apache Won't Reload

Check for syntax errors:
```bash
apache2ctl configtest
```

If there are errors, review the config file syntax.

## Security Best Practices

✅ **Implemented in above config:**
- ✅ TLS 1.2 and 1.3 only (no outdated SSLv3, TLS 1.0, 1.1)
- ✅ Strong cipher suites
- ✅ HSTS header (forces HTTPS for 1 year)
- ✅ All subdomains covered by single certificate
- ✅ Automatic renewal (never expires)

## Monitoring

Add to monitoring/alerting:
- Certificate expiration (check 30 days before)
- HTTPS availability on all domains
- SSL error logs

```bash
# Manual check of certificate expiration
openssl x509 -in /etc/letsencrypt/live/talishar.net/fullchain.pem -noout -dates
```

## References

- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Certbot Apache Plugin](https://certbot.eff.org/docs/using.html#apache)
- [Apache SSL Configuration](https://httpd.apache.org/docs/2.4/ssl/)
- [OWASP SSL/TLS Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/Transport_Layer_Protection_Cheat_Sheet.html)
