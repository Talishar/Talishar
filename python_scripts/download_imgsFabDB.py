import requests 
import urllib

def getFilename_fromCd(cd):
    """
    Get filename from content-disposition
    """
    if not cd:
        return None
    fname = re.findall('filename=(.+)', cd)
    if len(fname) == 0:
        return None
    return fname[0]


url = "https://fabdb2.imgix.net/cards/printings/"
 # important not to have a '/' at the end of the link
  
counter = 1
while counter<10:
    
    if counter < 10 :
        filename = "EVR00"+ str(counter)
    if counter <100:
        
        filename = "EVR0"+ str(counter)

    else :

        filename = "EVR"+ str(counter)
    r = urllib.request.urlretrieve(url + filename + ".png")
    output = open(filename, 'wb').write(r.read())
    output.close()                           
    counter+=1
