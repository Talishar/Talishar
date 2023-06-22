import json

header = """
function return_Hero_Type($Cardcode){
    switch($Cardcode){
"""
tail = """
        default:
            return False;}
            }
"""
# {your fab cube directory}/json/english/card.json 
with open("card.json") as json_file:
    data = json.load(json_file)
    for card in data:
        if "Hero" in card["types"]:
            young = False
            if "Young" in card["types"]:
                young = True
            for print_ in card["printings"]:
                id_ = print_["id"]
                header+=f"\n\tcase \"{id_}\":\n\t\treturn "
                if young == True:
                    header+= "Young;"
                else:
                    header+= "Adult;"
                header+="\n\t\tbreak;"
print(header+tail,file=open("hero_type.php","w"))