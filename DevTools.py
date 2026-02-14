import os
import pandas as pd

with open("./constants.php") as f:
    constants = f.readlines()[::-1]
all_pieces = {"ccs": [], "cs": []}
piece_name = ""
for i, line in enumerate(constants):
    if line[-len("Pieces()")-1:-1] == "Pieces()":
        if piece_name != "":
            all_pieces[piece_name] = all_pieces[piece_name][::-1]
        piece_name = line[len("function "):-len("Pieces()")-1]
        all_pieces[piece_name] = []
    if ("//" in line and piece_name != ""):
        all_pieces[piece_name].append(line.split(" - ")[1][:-1])
    if line[:len("$CCS_")] == "$CCS_" or line[:len("$CCS_")] == "$CSS_":
        all_pieces["ccs"].append(line.split("=")[0].strip()[len("$CCS_"):])
    if line[:len("$CS_")] == "$CS_":
        all_pieces["cs"].append(line.split("=")[0].strip()[len("$CS_"):])
all_pieces["ccs"] = all_pieces["ccs"][::-1]
all_pieces["cs"] = all_pieces["cs"][::-1]
all_pieces['TurnStats'] = ["DamageThreatened", "DamageDealt", "CardsPlayedOffense",
                           "CardsPlayedDefense", "CardsPitched", "CardsBlocked",
                           "ResourcesUsed", "ResourcesLeft", "CardsLeft", "DamageBlocked",
                           "Overblock", "LifeGained", "DamagePrevented"]
all_pieces["CardStats"] = ["CardID", "TimesPlayed", "TimesBlocked", "TimesPitched", "TimesHit",
                           "TimesCharged", "Dynamic1", "Dynamic2", "Dynamic3", "TimesKatsuDiscard"]

class_template = """class {cardID} extends Card {{
  function __construct($controller) {{
    $this->cardID = "{cardID}";
    $this->controller = $controller;
  }}
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {{
    return "";
  }}
}}"""

def unravel(L, zone_name):
    if L == "":
        return ""
    if isinstance(L, str):
        L = L.split(" ")
    
    data = {piece_name: [] for piece_name in all_pieces[zone_name]}
    for i, piece in enumerate(L):
        data[all_pieces[zone_name][i % len(all_pieces[zone_name])]].append(piece)
    M = max([len(data[k]) for k in data])
    for k in data:
        if len(data[k]) < M:
            data[k].append("MISSING")
    return pd.DataFrame.from_dict(data)

def parse_gamestate(gameid):
    file_loc = f"./Games/{gameid}/gamestate.txt"
    with open(file_loc) as f:
        lines = [L[:-1] for L in f.readlines()]
    results = {}
    results["Both"] = {}
    results["p1"] = {}
    results["p2"] = {}
    results["Both"]['lifes'] = lines[0].split(" ")
    
    results['p1']['hand'] = lines[1].split(" ")
    results['p1']['deck'] = lines[2].split(" ")
    results['p1']['char'] = unravel(lines[3], "Character")
    results['p1']['resources'] = lines[4].split(" ")
    results['p1']['arsenal'] = unravel(lines[5], "Arsenal")
    results['p1']['items'] = unravel(lines[6], "Item")
    results['p1']['auras'] = unravel(lines[7], "Aura")
    results['p1']['discard'] = unravel(lines[8], "Discard")
    results['p1']['pitch'] = lines[9].split(" ")
    results['p1']['banish'] = unravel(lines[10], "Banish")
    results['p1']['class state'] = unravel(lines[11], "cs")
    results['p1']['character effects'] = lines[12].split(" ")
    results['p1']['soul'] = unravel(lines[13], "Soul")
    results['p1']['card stats'] = unravel(lines[14], "CardStats")
    results['p1']['turn stats'] = unravel(lines[15], "TurnStats")
    results['p1']['allies'] = unravel(lines[16], "Ally")
    results['p1']['permanents'] = unravel(lines[17], "Permanent")
    results['p1']['settings'] = lines[18].split(" ")
    
    results['p2']['hand'] = lines[19].split(" ")
    results['p2']['deck'] = lines[20].split(" ")
    results['p2']['char'] = unravel(lines[21], "Character")
    results['p2']['resources'] = lines[22].split(" ")
    results['p2']['arsenal'] = unravel(lines[23], "Arsenal")
    results['p2']['items'] = unravel(lines[24], "Item")
    results['p2']['auras'] = unravel(lines[25], "Aura")
    results['p2']['discard'] = unravel(lines[26], "Discard")
    results['p2']['pitch'] = lines[27].split(" ")
    results['p2']['banish'] = unravel(lines[28], "Banish")
    results['p2']['class state'] = unravel(lines[29], "cs")
    results['p2']['character effects'] = lines[30].split(" ")
    results['p2']['soul'] = unravel(lines[31], "Soul")
    results['p2']['card stats'] = unravel(lines[32], "CardStats")
    results['p2']['turn stats'] = unravel(lines[33], "TurnStats")
    results['p2']['allies'] = unravel(lines[34], "Ally")
    results['p2']['permanents'] = unravel(lines[35], "Permanent")
    results['p2']['settings'] = lines[36].split(" ")
    
    results['Both']['landmarks'] = unravel(lines[38], "Landmark")
    results['Both']['combat chain'] = unravel(lines[44], "CombatChain")
    results['Both']['ccs'] = unravel(lines[45], "ccs")
    results['Both']['current turn effects'] = unravel(lines[46], "CurrentTurnEffects")
    results['Both']['layers'] = unravel(lines[52], "Layer")
    num_chain_links = int(lines[56].strip())
    for i in range(num_chain_links):
        results['Both'][f'chain link {i+1}'] = unravel(lines[57+i], "ChainLinks")
    results['Both'][f'chain link summary'] = unravel(lines[57 + num_chain_links], "ChainLinkSummary")
    results["P2IsAI"] = lines[75+num_chain_links]
    results["InfiniteHP"] = lines[76+num_chain_links]
    
    results['p1']['inventory'] = lines[72 + num_chain_links].split(" ")
    results['p2']['inventory'] = lines[73 + num_chain_links].split(" ")
    return results

def FindFunction(function_name, search_root="./"):
    for root, dirs, files in os.walk(search_root):
        for file in files:
            fpath = os.path.join(root, file)
            if "Classes/" not in fpath:
                try:
                    with open(fpath) as f:
                        for line in f:
                            if line.startswith(f"function {function_name}"):
                                return line.strip(" \n{")
                except UnicodeDecodeError:
                    print(f"Can't read {fpath}")
    return "FAILED"
                    
def AwaitWrapper(function_def, tab="  "):
    L = len("function ")
    orig_name = function_def.split("(")[0][L:]
    function_name = orig_name + "Await"
    args = function_def.split("(")[1].split(")")[0]
    args = args.split(", ")
    ret = f"function {function_name}($player) {{\n{tab}global $dqVars;\n"
    play_line = f"{tab}{orig_name}("
    for arg in args:
        arg_name = arg.split("=")[0].strip()[1:]
        if ("=") in arg:
            default_value = arg.split("=")[1].strip()
        else: default_value = None
        if (arg_name != "player"):
            if default_value is None:
                ret += f'{tab}${arg_name} = $dqVars["{arg_name}"];\n'
            else:
                ret += f'{tab}${arg_name} = $dqVars["{arg_name}"] ?? {default_value};\n'
        play_line += f"${arg_name}, "
    play_line = play_line[:-2] +  ")"
    ret += f"{play_line};\n}}"
    return ret

def Compile(function_name, tab="  ", dest="./DecisionQueue/AwaitEffects.php"):
    function_def = FindFunction(function_name)
    if function_def != "FAILED":
        wrapper = AwaitWrapper(function_def, tab)
        await_def = wrapper.split("\n")[0].strip(" {")
        with open(dest, "r") as f:
            if await_def in f.read():
                print("The wrapper already exists!")
                return
        with open(dest, "a") as f:
            f.write("\n\n" + wrapper)
            print("Successfully written to file")
            return
    else:
        print("Failed to find the function")