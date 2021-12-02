using UnityEngine;
using System.Collections;

public class RotateAround : MonoBehaviour {
	public Transform rot_center;
	// Use this for initialization
	void Start () {
	
	}
	
	// Update is called once per frame
	void Update () {
		this.transform.RotateAround(rot_center.position,Vector3.up,.25f);
	}
}
