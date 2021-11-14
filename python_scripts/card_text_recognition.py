import pytesseract
from pytesseract import Output
import cv2

from PIL import Image 


from os import listdir
from os.path import isfile, join



mypath = "."
onlyfiles = [f for f in listdir(mypath) if isfile(join(mypath, f)) and "jpg" in f ]

csvfile = "recognized_values.csv"
with open (csvfile,"a") as f:
    print("filename;cost;attack;defense;pitch",file=f)


for filename in onlyfiles:


## cost
    print("--------------------------------------------------------")
    img = Image.open(filename)
    #img.show()



    width, height = img.size


    # Black or White Filter
    gray = img.convert('L')
    bw = gray.point(lambda x: 0 if x<128 else 255, '1')
    basename ,fileend = filename.split(".")
    #bw.show()
    bw.save(basename+"_changed."+"png")


    image_data = img.load()



    img = Image.open(filename)
    left    =width -60
    right   =width -45
    top     =45
    bottom  =65
    img_res = img.crop((left, top, right, bottom)) 
    file_cost_crop = filename+"cost.png"
    img_res.save(file_cost_crop, "PNG", quality=100, optimize=True, progressive=True)
    #img_res.show()
    img_cost = cv2.imread(file_cost_crop)
    d = pytesseract.image_to_data(img_cost, output_type=Output.DICT, lang = 'eng',config="-c tessedit_char_whitelist=0123456789")
    
    #basically done

    cost = pytesseract.image_to_string(file_cost_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 8")
    
    ## pitch

## attack

    left = 73
    right = 100
    top = height - 60
    bottom = height -20
    img_res = img.crop((left, top, right, bottom))
    file_attack_crop = filename+"attack.png"
    img_res.save(file_attack_crop, "PNG", quality=100, optimize=True, progressive=True)

    attack = pytesseract.image_to_string(file_attack_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 8")

    defense = 0
## defense

    left =  width - 100
    right = width - 73
    top = height - 60
    bottom = height -20
    img_res = img.crop((left, top, right, bottom))
    file_defense_crop = filename+"attack.png"
    img_res.save(file_attack_crop, "PNG", quality=100, optimize=True, progressive=True)

    defense = pytesseract.image_to_string(file_attack_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 8")


    with open (csvfile,"a") as f:
        csvline = f"{basename};{cost};{attack};{defense}".replace(" ", "").replace("\n", "").strip() 
        print(csvline)
        print(csvline,file=f)