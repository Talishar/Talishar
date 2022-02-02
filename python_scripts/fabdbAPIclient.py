import requests 
import shutil 

import requests
import re

import json

set_code = {
    "WTR",
    "ARC",
    "CRU",
    "MON",
    "ELE",
    "EVR"

 }



def main():
    for code in set_code:
        getCardJson(code)

        db, np_url = getJsonDict(code)

        while np_url:
            for card_dict in db:
                num_ = card_dict["printings"][0]['sku']["number"]
                im_url = card_dict["image"]
                downloadCardImage(num_, code, im_url)
            getCardJson(code,next_page_url=np_url)
            db, np_url = getJsonDict(code)


def downloadCardImage(num_, set_, image_url ):

    filename=f"{set_}{num_}.png"
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
        print('\t', image_url )



def getCardJson(code, next_page_url = None):
    
    url = f"https://api.fabdb.net/cards?set={code}&per_page=100"
    if next_page_url:
        url = f"https://api.fabdb.net"+next_page_url
    r = requests.get(url, allow_redirects=True)
    
    filename = f"fabdb_{code}.json"

    open(filename, 'wb').write(r.content)

def getJsonDict(code):
    filename = f"fabdb_{code}.json"
    db = json.loads(open(filename, 'r').read())
    return db["data"], db["links"]["next"]
    

if __name__ == "__main__":
    main()