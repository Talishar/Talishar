import pytesseract
from pytesseract import Output
import cv2

from PIL import Image 


from os import listdir, remove, mkdir
from os.path import isfile, join

from colormath.color_objects import XYZColor, AdobeRGBColor, CMYColor, CMYKColor, sRGBColor
from colormath.color_conversions import convert_color


mypath = "."
onlyfiles = [f for f in sorted(listdir(mypath)) if isfile(join(mypath, f)) and "jpg" in f ]
mkdir("crops")

csvfile = "recognized_values.csv"
with open (csvfile,"a") as f:
    print("filename,title,cost,attack,defense,pitch",file=f)


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
    


## attack

    left = 73
    right = 100
    top = height - 60
    bottom = height -20
    img_res = img.crop((left, top, right, bottom))
    file_attack_crop = filename+"attack.png"
    img_res.save(file_attack_crop, "PNG", quality=100, optimize=True, progressive=True)

    attack = pytesseract.image_to_string(file_attack_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 8")

## defense

    left =  width - 100
    right = width - 73
    top = height - 60
    bottom = height -20
    img_res = img.crop((left, top, right, bottom))
    file_defense_crop = filename+"defense.png"
    img_res.save(file_defense_crop, "PNG", quality=100, optimize=True, progressive=True)

    defense = pytesseract.image_to_string(file_defense_crop, lang = 'eng',config="-c tessedit_char_whitelist=0123456789 --psm 8")

## title

    left = 100
    right = 350
    top = 40
    bottom = 70
    img_res = img.crop((left, top, right, bottom))
    file_title_crop = filename+"title.png"
    img_res.save(file_title_crop, "PNG", quality=100, optimize=True, progressive=True)

    title = pytesseract.image_to_string(file_title_crop, lang = 'eng',config="-c tessedit_char_blacklist=0123456789 --psm 7").replace("\n", "") #treat crop as a line
    title = "\""+title+"\"" # use encoding to include commas and commatas
    print(title)




## pitch
    image = Image.open(filename)
    print(image.mode)
    #if image.mode == 'RGB':
    #   image = image.convert('CMYK')

    
    r, g, b, a = image.getpixel((225,30)) #middle of picture, then 30 from the top, measured with gimp
    print("rgba",r,g,b,a)
    srgb = sRGBColor(r, g, b,a)

    cmy = convert_color(srgb, CMYColor)
    c,m,y = cmy.get_value_tuple()
    print("cmy", c, y, m)
    cmyk = convert_color(cmy, CMYKColor)
    c,m,y,k = [x*100 for x in cmyk.get_value_tuple() ]
    """
    for yellow and red pitches cyan is usually zero whereas for blue its close to 1
    red has high yellow and red numbers, both close to 0.9
    yellow has a low red value (close to 0.1), but yellow value is really close to 100 
    """
    print("cmyk",c,m,y,k)
    pitch = "None"
    if 100-c<5:
        pitch = "blue"
    elif 100-y <5  :
        pitch = "yellow"
    elif 100-r<20 and 100-y<20:
        pitch = "red"
    print(pitch)



    with open (csvfile,"a") as f:
        csvline = f"{basename},{title},{cost},{attack},{defense},{pitch}"
        csvline = csvline.replace("\n", "").replace("\v", "").replace("\x0b", "").strip() 
        print(csvline)
        print(csvline,file=f)
    

    crop_title = "crops/"+basename+"_cropped."+"png"
    left = 50
    right = 400
    top = 90
    bottom = 360
    if basename == "MON000" or basename == "ELE000":
        left = 160
        right = 360
        top = 80
        bottom = 530
    
    
    
    
    img_res = img.crop((left, top, right, bottom))
    img_res.save(crop_title, "PNG", quality=100, optimize=True, progressive=True)