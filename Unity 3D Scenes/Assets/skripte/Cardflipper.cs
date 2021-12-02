/*
 * Flips a card over in an animated motion. Script needs to be adapted to be trigged by something else. 
 Currently its triggered by the global variable Globals.flip == 1.
 Author: Kevin Wolf || MagicalSpaceWizard
 Version: 1.0
 */



using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Cardflipper : MonoBehaviour
{
    public float smooth = 1f;
    float degreesPerSecond = 180;
    float timer = 0;
    float timer2 = 0;
    bool timer3 = false;
    bool timer4 = false;

    private Quaternion targetRotation;

    // Use this for initialization
    void Start()
    {

    }

    // Update is called once per frame
    void Update()
    {
        if (Globals.flip == 1 && this.tag == "p1")
        {
            //rotieren
            if (timer <= 1)
            {
                transform.Rotate(new Vector3(0, 0, degreesPerSecond) * Time.deltaTime, Space.World);
                timer = timer + 1 * Time.deltaTime;
                
            }
            
            
            
            //Y Bewegen
            if (timer2 <= 0.6 && timer4 == false)
            {
                Vector3 newPosition = transform.position; // We store the current position
                newPosition.y = newPosition.y + 2 * Time.deltaTime; // We set a axis, in this case the y axis
                transform.position = newPosition; // We pass it back
                

            }
            
            if (timer2 >= 0.6 && timer3 == false)
            {
                timer4 = true;
                Vector3 newPosition = transform.position; // We store the current position
                newPosition.y = newPosition.y - 2 * Time.deltaTime; // We set a axis, in this case the y axis
                transform.position = newPosition; // We pass it back


            }
            timer2 = timer2 + 1f * Time.deltaTime;

          

            
            if (timer2 >= 1.2)
            {
                timer3 = true;

            }
            
            
        }

        if (Globals.flip == 2 && this.tag == "p2")
        {
            //rotate
            if (timer <= 1)
            {
                transform.Rotate(new Vector3(0, 0, degreesPerSecond) * Time.deltaTime);
                timer = timer + 1 * Time.deltaTime;

            }



            //Y move
            if (timer2 <= 0.6 && timer4 == false)
            {
                Vector3 newPosition = transform.position; // We store the current position
                newPosition.y = newPosition.y + 1 * Time.deltaTime; // We set a axis, in this case the y axis
                transform.position = newPosition; // We pass it back


            }

            if (timer2 >= 0.6 && timer3 == false)
            {
                timer4 = true;
                Vector3 newPosition = transform.position; // We store the current position
                newPosition.y = newPosition.y - 1 * Time.deltaTime; // We set a axis, in this case the y axis
                transform.position = newPosition; // We pass it back


            }
            timer2 = timer2 + 1f * Time.deltaTime;

          


            if (timer2 >= 1.2)
            {
                timer3 = true;

            }


        }



    }
}

   


