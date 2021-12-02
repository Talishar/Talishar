using System.Collections;
using System.Collections.Generic;
using UnityEngine;


//Hier werden global nutzbare Variablen erzeugt und verwalten
static class Globals
{
    // global int

    public static int flip = 0;




    // global int using get/set
    static int _getsetcounter;
    public static int getsetcounter
    {
        set { _getsetcounter = value; }
        get { return _getsetcounter; }
    }
}
