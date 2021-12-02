/*
 * Rotates the camera around the object this is assigned to. 
 Author: Kevin Wolf | MagicalSpaceWizard
 Version: 1.0
 */

using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class drehen : MonoBehaviour {
    public float RotationSpeed = 25.0f;
    
    // Use this for initialization
    void Start () {
		
	}
	
	// Update is called once per frame
	void Update () {
        //transform.Rotate(Vector3.up * (RotationSpeed * Time.deltaTime));

    }

    void FixedUpdate()
    {
        Rigidbody rb = GetComponent<Rigidbody>();
        rb.MoveRotation(rb.rotation * Quaternion.Euler(0, RotationSpeed * Time.fixedDeltaTime, 0));
    }
}
