from PIL import Image
import os.path, sys

from os import listdir
from os.path import isfile, join
import cv2

mypath = "."
path = mypath
dirs = [f for f in sorted(listdir(mypath)) if isfile(join(mypath, f)) and "jpg" in f ]
current_prefix = ''

def cropmerge():
    for item in dirs:
        fullpath = os.path.join(path,item)
        if os.path.isfile(fullpath):
            im = Image.open(fullpath)
            f, e = os.path.splitext(fullpath)

            # Top Layer
            imCropTop = im.crop((0, 0, 500, 372))
            imCropTop.save(f + '_croptop.png', "PNG", quality=100, optimize=True, progressive=True)

            # Bottom Layer
            imCropBot = im.crop((0, 550, 500, 800))
            imCropBot.save(f + '_cropbot.png', "PNG", quality=100, optimize=True, progressive=True)

            # Concatenate
            img1 = cv2.imread(f + '_croptop.png')
            img2 = cv2.imread(f + '_cropbot.png')

            imMerge = cv2.vconcat([img1, img2])
            y=0
            x=0
            h=450
            w=450
            imCrop = imMerge[y:y+h, x:x+w]

            cv2.imwrite(f + '_concat.png', imCrop)

cropmerge()

cv2.waitKey(0)
cv2.destroyAllWindows()
