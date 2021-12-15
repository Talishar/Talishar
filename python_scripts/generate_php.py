
outfile = open("card_names.php","w")
function_header ="""<?php
function CardName ($cardID, $showPitch=true)
{
    $arr = str_split($cardID, 3);
    $set = $arr[0];
    $num = $arr[1];
    
"""
print(function_header,file=outfile)
import csv
file = open("recognized_values.csv")
csvreader = csv.reader(file)
header = next(csvreader)
print(header)
rows = []
settonumbertovalues = {}
for row in csvreader:
    print(row)
    basename = row[0]
    title = row[1]
    pitch = row[5]
    cardset = basename[0:3]
    setnum = basename[3:]
    if cardset not in settonumbertovalues.keys():
        settonumbertovalues[cardset] = {}
    settonumbertovalues[cardset][setnum]= {}
    settonumbertovalues[cardset][setnum]["title"] = title
    settonumbertovalues[cardset][setnum]["pitch"] = pitch
    rows.append(row)

#print(settonumbertovalues)
settonumbertovalues["MON"]['000']["title"] = "The Great Library of Solana"
settonumbertovalues["MON"]['000']["pitch"] = "None"
settonumbertovalues["ELE"]['000']["title"] = "Korshem, Crossroad of Elements"
settonumbertovalues["ELE"]['000']["pitch"] = "None"


nametonumber ={}
for set_name,dict_num in settonumbertovalues.items():
    for number, dict_val in dict_num.items():
        title = dict_val["title"][:-1]
        if title not in nametonumber.keys():
            nametonumber[title] = []
        nametonumber[title].append(set_name+number)




maxtitle=max(len(settonumbertovalues[cardset][setnum]["title"]) for cardset in settonumbertovalues.keys() for setnum in settonumbertovalues[cardset].keys()) 
print ("Longest Title:",maxtitle)
for cardset in settonumbertovalues.keys():
    ifstring = (
        f'if ($set == \"{cardset}\")\n'
        "\t{  switch($num)"
        "\t   {\n"
        )
    cases = ""
    for setnum in settonumbertovalues[cardset].keys():
        title = settonumbertovalues[cardset][setnum]["title"]
        pitch = settonumbertovalues[cardset][setnum]["pitch"]
        if pitch == "None":
            cases += f'\t\tcase "{setnum}": return "{title[:-1]}";\n'
            continue
        cases += (f'\t\tcase "{setnum}": if ($showPitch == false) return "{title[:-1]}";\n'
        f'\t\t\telse return "{title[:-1]} ({pitch})";\n'
        )
    ifstring_end = (
        "\t\t   }"
        "\t}"
    )
    print(ifstring,file = outfile)
    print(cases, file = outfile)
    print(ifstring_end, file= outfile )
    


function_tail = """
        return \"\";    }
             """
print(function_tail, file= outfile)

function_header ="""
function cardID ($cardName)
{
\tswitch($cardName){
  
"""
cases = ""
for name, arr in nametonumber.items():
    print(arr,name)
    if len(arr)==0:
        cases += f'\t\t case "{name}": return array();'
    cases += f'\t\t case "{name}": return array('
    for s in arr[:-1]:
        cases+=f'"{s}"'+","
    cases+=f'"{arr[-1]}"'
    cases += ");\n"

function_tail = """
\t\t}\n
\t return array(); }

?>
"""
print(function_header, file= outfile)
print(cases, file=outfile)

print(function_tail, file= outfile)

