using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;


public class Countdice : MonoBehaviour {

    public Text txt;
	// Use this for initialization
	void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
        GameObject[] npcs = GameObject.FindGameObjectsWithTag("wuerfel");
        int numberOfNPCS = npcs.Length;
        txt.text= ("SCORE: " + numberOfNPCS);
    }
}
