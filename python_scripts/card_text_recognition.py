import pytesseract
from pytesseract import Output
import cv2

from PIL import Image 


from os import listdir
from os.path import isfile, join



mypath = "."
onlyfiles = [f for f in listdir(mypath) if isfile(join(mypath, f)) and "jpg" in f ]

for filename in onlyfiles:


## cost
    print("--------------------------------------------------------")
    img = Image.open(filename)
    width, height = img.size
    left    =width -60
    right   =width -45
    top     =45
    bottom  =65
    img_res = img.crop((left, top, right, bottom)) 
    file_cost_crop = filename+"cost.png"
    img_res.save(file_cost_crop, "PNG", quality=100, optimize=True, progressive=True)

    img_cost = cv2.imread(file_cost_crop)
    d = pytesseract.image_to_data(img_cost, output_type=Output.DICT, lang = 'eng',config="-c tessedit_char_whitelist=0123456789")
    
    #basically done

    print(pytesseract.image_to_string(file_cost_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 11"))


    
    ## pitch

    ## attack

    ##defense