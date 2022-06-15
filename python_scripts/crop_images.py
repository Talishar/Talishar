from PIL import Image
import os.path, sys

from os import listdir
from os.path import isfile, join

mypath = "."
path = mypath
dirs = [f for f in sorted(listdir(mypath)) if isfile(join(mypath, f)) and "png" in f ]

def crop():
    for item in dirs:
        fullpath = os.path.join(path,item)
        if os.path.isfile(fullpath):
            im = Image.open(fullpath)
            f, e = os.path.splitext(fullpath)
            imCrop = im.crop((50, 100, 400, 370))
            imCrop.save(f + '_cropped.png', "PNG", quality=100)

crop()
