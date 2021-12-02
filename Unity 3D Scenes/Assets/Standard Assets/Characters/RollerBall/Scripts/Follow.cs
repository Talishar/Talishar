using System.Collections;
using System.Collections.Generic;
using UnityEngine;


public class Follow : MonoBehaviour
{
    
    public Transform target;    
    public float speed = 10;
    public float distanceX = 1;
    public float distanceY = 1;

    void Update()
    {
        //Abstand Ermitteln X Achse
        Vector3 xtarget = new Vector3(target.transform.position.x, 0, 0);
        Vector3 xobjekt = new Vector3(transform.position.x, 0, 0);
        float abstandx = Vector3.Distance(xtarget, xobjekt);
        print("Distance X: " + abstandx);
        //Abstand Ermitteln Y Achse
        Vector3 ytarget = new Vector3(0, target.transform.position.y, 0);
        Vector3 yobjekt = new Vector3(0, transform.position.y, 0);
        float abstandy = Vector3.Distance(ytarget, yobjekt);
        print("Distance Y: " + abstandy);
        //Wenn der Abstand auf beiden Achsten größer des eingestellten Wertes ist
        if ((abstandx >= distanceX) && (abstandy >= distanceY)) {
            // Ans target heranbewegen
            float step = speed * Time.deltaTime;       
            transform.position = Vector3.MoveTowards(transform.position, target.position, step);
        }

       
    }
}
