using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FlipCards : MonoBehaviour {

    
    // Use this for initialization
    void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {

       

        if (Input.GetKeyDown("space"))
        {
            
            Globals.flip++;
            Debug.Log(Globals.flip);
        }
        if (Globals.flip == 3)
        {
            GameObject varGameObject = GameObject.Find("Main Camera");
            varGameObject.GetComponent<OrbitPlayer>().enabled = false;

        }

      


    }
}
