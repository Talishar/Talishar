using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class wuerfeln1 : MonoBehaviour {
    public Transform wuerfel;
    public GameObject myPrefab2;
    
    
 

    // Use this for initialization
    void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
        if (Input.GetMouseButtonDown(0) == true)
        {
            //  Vector3 position = new Vector3(-4, 4, 6);
            // Instantiate(wuerfel, position, wuerfel.rotation);

            Instantiate(myPrefab2, new Vector3(-4, 8, 4), myPrefab2.transform.rotation);


        }

        if (Input.GetMouseButtonDown(1) == true)
        {



            Destroy(GameObject.FindWithTag("wuerfel"));

        }


    }


}
