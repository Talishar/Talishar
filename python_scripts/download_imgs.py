import requests 
import shutil 

import requests
import re

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


urls = {
    "https://fabtcg.com/resources/card-galleries/welcome-rathe-unlimited-booster": "WTR",
    "https://fabtcg.com/resources/card-galleries/arcane-rising-unlimited-booster": "ARC",
    "https://fabtcg.com/resources/card-galleries/crucible-war-booster": "CRU",
    "https://fabtcg.com/resources/card-galleries/monarch-booster-unlimited": "MON",
    "https://fabtcg.com/resources/card-galleries/tales-aria-booster": "ELE"

 } # important not to have a '/' at the end of the link
for url, code in urls.items():

    r = requests.get(url, allow_redirects=True)
    filename = url.split("/")[-1]
    
    open(filename, 'wb').write(r.content)
    counter = 1
    exceptions = 0
    for line in open(filename):
        if "https://storage.googleapis.com/fabmaster/media/images/" in line:
            if "450" in line:
                print("\c",line)
                index0 = line.index("href=\"") +len("href=\"")
                index1 = line.index("450.png")+ len("450.png")
                image_url = line[index0:index1]
                print(index0, index1, image_url    )

                #filename = image_url.split("/")[-1]

                fileending = image_url.split(".")[-1] #file ending
                filename = code+"{:03d}".format(counter - exceptions)+"."+"jpg"

                # Exception for Fabled cards
                if code == "MON" and counter == 88: #Exception 
                    filename = code+"{:03d}".format(0)+"."+"jpg"
                    exceptions += 1
                if code == "ELE" and counter == 112: #Exception 
                    filename = code+"{:03d}".format(0)+"."+"jpg"
                    exceptions += 1
                if code == "WTR" and counter == 226: #Exception 
                    filename = code+"{:03d}".format(0)+"."+"jpg"
                    exceptions += 1
                if code == "ARC" and counter == 219: #Exception 
                    filename = code+"{:03d}".format(0)+"."+"jpg"
                    exceptions += 1
                if code == "CRU" and counter == 158: #Exception 
                    filename = code+"{:03d}".format(0)+"."+"jpg"
                    exceptions += 1
                    
                    
                counter+=1

                # Open the url image, set stream to True, this will return the stream content.
                r = requests.get(image_url, stream = True)

                # Check if the image was retrieved successfully
                if r.status_code == 200:
                    # Set decode_content value to True, otherwise the downloaded image file's size will be zero.
                    r.raw.decode_content = True
                    
                    # Open a local file with wb ( write binary ) permission.
                    with open(filename,'wb') as f:
                        shutil.copyfileobj(r.raw, f)
                        
                    print('Image sucessfully Downloaded: ',filename)
                else:
                    print('Image Couldn\'t be retreived')
                