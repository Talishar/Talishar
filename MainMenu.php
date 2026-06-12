<?php
// Legacy entry point of the pre-React frontend. The React app at the site
// root replaced it, but old bookmarks, browser history, and external links
// still request this path — without this stub every such hit produced an
// AH01071 "Unable to open primary script" error in the Apache log.
http_response_code(301);
header("Location: /");
exit;
