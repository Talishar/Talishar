using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class wuerfelspeed : MonoBehaviour {
    private Rigidbody rb;
    Vector3 m_EulerAngleVelocity;
    bool ausblenden = false;

    // Use this for initialization
    void Awake () {
        rb = gameObject.GetComponent<Rigidbody>();
        rb.AddForce(10, 0, 0, ForceMode.Impulse);

        m_EulerAngleVelocity = new Vector3(0, Random.Range(-300f, 800.0f), 0);
        Quaternion deltaRotation = Quaternion.Euler(m_EulerAngleVelocity * Time.fixedDeltaTime);
        rb.MoveRotation(rb.rotation * deltaRotation);
        rb.AddTorque(Random.Range(0, 6000.0f), Random.Range(0, 6000.0f), Random.Range( 0, 6000.0f));
    }

    // Update is called once per frame
    void Update() {

     
    }

    private void OnCollisionEnter(Collision collision) //Ereignis bei Collision mit Wand, Kugel muss Rigidbody sein
    {
        if (collision.collider.tag == "killer")
        {
          Debug.Log("Game Over");
        }
    }

    }
