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
    img.show()



    width, height = img.size
    image_data = img.load()
    for loop1 in range(height):
        for loop2 in range(width):
            try:
                r,g,b,d = image_data[loop2,loop1]
                """
                WHat to filter
                """
                if r > 40 or g > 40  or b > 40 :
                    image_data[loop2,loop1] = 255,255,255,255
            except:
                print(loop1,loop2)
    basename ,fileend = filename.split(".")
    img.show()
    img.save(basename+"_changed."+"png")




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

    cost = pytesseract.image_to_string(file_cost_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 11")
    
    ## pitch

## attack

    left = 73
    right = 100
    top = height - 60
    bottom = height -20
    img_res = img.crop((left, top, right, bottom))
    file_attack_crop = filename+"attack.png"
    img_res.save(file_attack_crop, "PNG", quality=100, optimize=True, progressive=True)

    attack = pytesseract.image_to_string(file_attack_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 11")
    ##defense

    defense = 0

    with open (csvfile,"a") as f:
        print(f"{filename};{cost};{attack};{defense}".replace(" ", "").replace("\n", "").strip() )
        print(f"{filename};{cost};{attack};{defense}".replace(" ", "").replace("\n", "").strip() ,file=f)