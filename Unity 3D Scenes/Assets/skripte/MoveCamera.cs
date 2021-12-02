using System.Collections;
using System.Collections.Generic;
using UnityEngine;


public class MoveCamera : MonoBehaviour {
    public Transform target; //choose the target camera to which the camera this script is run on should be transitioned
    public Transform target2;

    //the movespeed of the rotation
    public float movespeedrotation = 9f;
    public int movespeed = 3;

    // Use this for initialization
    void Start () {
        Quaternion targetRotation = target.transform.localRotation;
        Quaternion target2Rotation = target2.transform.localRotation;
    }
	
	// Update is called once per frame
	void Update () {

         

        if (Globals.flip == 3) //this activates the camera transition
        {

            

            //MOVEMENT Move to Position of Target Camera each Update

            transform.position = Vector3.MoveTowards(transform.position, target.position, movespeed * Time.deltaTime);
            //ROTATION rotate to rotation of target camera each Update
        
            Quaternion targetRotation = target.transform.localRotation;
            transform.localRotation = Quaternion.RotateTowards(transform.localRotation, targetRotation, Time.deltaTime * movespeedrotation);

            
        }
        if (Globals.flip == 4) //this activates the camera transition
        {



            //MOVEMENT Move to Position of Target Camera each Update

            transform.position = Vector3.MoveTowards(transform.position, target2.position, movespeed * Time.deltaTime);
            //ROTATION rotate to rotation of target camera each Update

            Quaternion target2Rotation = target.transform.localRotation;
            transform.localRotation = Quaternion.RotateTowards(transform.localRotation, target2Rotation, Time.deltaTime * movespeedrotation);


        }
    }
}
