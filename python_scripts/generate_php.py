
outfile = open("card_names.php","w")
function_header ="""function CardName ($cardID)
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
    
    cardset = basename[0:3]
    setnum = basename[3:]
    if cardset not in settonumbertovalues.keys():
        settonumbertovalues[cardset] = {}
    settonumbertovalues[cardset][setnum]= {}
    settonumbertovalues[cardset][setnum]["title"] = title
    rows.append(row)

print(settonumbertovalues)

for cardset in settonumbertovalues.keys():
    ifstring = (
        f'if ($set == \"{cardset}\")\n'
        "\t{  switch($num)"
        "\t   {\n"
        )
    cases = ""
    for setnum in settonumbertovalues[cardset].keys():
        title = settonumbertovalues[cardset][setnum]["title"]
        cases += f'\t\tcase "{setnum}": return "{title}";\n'
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