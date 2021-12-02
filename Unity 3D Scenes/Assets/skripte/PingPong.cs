using System.Collections;
using System.Collections.Generic;
using UnityEngine;



public class PingPong : MonoBehaviour {

    public float thrust = 10.0f;
    public Rigidbody kugel;
    public int ping = 20; //Aufgabenstellung gibt 20 Kollisionen vor
    int pong = 0;
   
    // Use this for initialization
    void Start () {
        kugel = GetComponent<Rigidbody>();
        kugel.AddForce(thrust, 0, 0, ForceMode.Impulse);
	}

    private void OnCollisionEnter(Collision collision) //Ereignis bei Collision mit Wand, Kugel muss Rigidbody sein
    {
        Debug.Log("Kollidiert mit" + collision.collider.tag);
        if (pong < ping && collision.collider.tag == "wand_links") //Wenn die Kugel mit der linken Wand kollidiert und noch Kollisionen übrig sind...
        {
            kugel = GetComponent<Rigidbody>();
            kugel.AddForce(-thrust, 0, 0, ForceMode.Impulse); //...wird sie nach rechts gestoßen
            pong++;
            Debug.Log("Ping");
        }
        if (pong < ping && collision.collider.tag == "wand_rechts") //Wenn die Kugel mit der rechten Wand kollidiert und noch Kollisionen übrig sind...
        {
            kugel = GetComponent<Rigidbody>();
            kugel.AddForce(thrust, 0, 0, ForceMode.Impulse); //...wird sie nach links gestoßen
            pong++;
            Debug.Log("Pong");
        }
        Debug.Log("Kollisionen verbleibend " + (ping - pong));

        if (pong == ping) Debug.Log("Maximale Anzahl der Kollisonen erreichtt"); //Sind keine Kollisionen mehr übrig, ist die Ausführung abgeschlossen und die Kugel reagiert auf keine weiteren Kollisionstrigger mehr
    }
    // Update is called once per frame
    void Update () {
		
	}
}
