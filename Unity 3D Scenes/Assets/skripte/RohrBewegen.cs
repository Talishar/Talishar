using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class RohrBewegen : MonoBehaviour {

    Rigidbody m_Rigidbody;
    public float m_Speed = 5f;
    int z = 0;
    int z2 = 0;
    bool toggle;
    bool toggle2;
    // Use this for initialization
    void Start () {
        //Fetch the Rigidbody from the GameObject with this script attached
        m_Rigidbody = GetComponent<Rigidbody>();
    }

    // Update is called once per frame
    void FixedUpdate() {


        if (z < 140) toggle = false;
        if(z >= 140) toggle = true;

        if (z >= 140) toggle2 = true;
        if (z2 >= 140)
        {
            z = 0;
            toggle2 = false;
            toggle = false;
        }

        if (toggle == false) {
            Vector3 m_Input = new Vector3(0, 0, -1f);
            m_Rigidbody.MovePosition(transform.position + m_Input * Time.deltaTime * m_Speed);
            z2 = 0;
            z++;
        }

        if (toggle2 == true)
        {
            Vector3 m_Input = new Vector3(0, 0, 1f);
            m_Rigidbody.MovePosition(transform.position + m_Input * Time.deltaTime * m_Speed);
            z2++;
        }
    }
}
