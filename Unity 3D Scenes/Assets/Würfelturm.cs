using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Würfelturm : MonoBehaviour {

    public Transform wuerfel;
    public int breite;
    public int hoehe;

    // Use this for initialization
    void Start () {

        for (int y = 1; y <= hoehe; y++) //Y der Reihen hochzählen bis Höhe erreicht ist
        {
            for (int x = 1; x <= breite; x++) //Reihen von Würfeln erzeugen
            {
                Vector3 position = new Vector3(x , y , 0);
                Instantiate(wuerfel, position, wuerfel.rotation);
            }
          
        }


    }
	
	// Update is called once per frame
	void Update () {
		
	}
}
