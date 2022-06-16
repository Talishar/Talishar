import requests 
import json
import urllib.request
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

sets = [
    "WTR",
    "ARC",
    "CRU",
    "MON",
    "ELE",
    "EVR"
    "RVD",
    "DVR",
    "UPR"
 ] 


fabdb_api = "https://api.fabdb.net"
for code in sets:
    url_ = fabdb_api+f"/cards?set={code}&per_page=100"
    counter = 0
    while True:
        resp = requests.get(url_)
        print(resp.text)
        json_dic = json.loads(resp.text)
        print("rofl")
        for card in json_dic['data']:
            image_url = card['image']
            card_name = card['name']
            cardcode = f"{code}{'{:03d}'.format(counter)}"
            urllib.request.urlretrieve(
                image_url, 
                f"{cardcode}.jpg")
            print(cardcode)
            counter+=1
        if json_dic['links']['next']!= None:
            url_ = fabdb_api+json_dic['links']['next']
            continue
        else:
            break
