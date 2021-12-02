using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class rollit : MonoBehaviour
{
    //für den Würfel
    public GameObject bullet;

    //die Geschwindigkeit der Kugel
    public float bulletSize = 0.3f;
    //für die Drehung
    public int moveSpeed = 10;
    

   

    void Fire()
    {
        Transform wuerfel = bullet.transform;
        GameObject myBullet = Instantiate(bullet, transform.position, wuerfel.rotation);
        //Kraft anwenden in die Vorwärtsrichtung, dadurch bewegt sich die Kugel
        //die Geschwindigkeit ist variabel
        myBullet.transform.localScale *= bulletSize;
        //Instantiate(bullet, transform.position, wuerfel.rotation);

        //Geschwindigkeit zurücksetze
        bulletSize = 0.3f;
        //die Kugel nach 5 Sekunden zerstören
    
    }

    void Update()
    {
        //für die Rotation

        //wenn Fire1 gehalten wird, erhöht sich die Geschwindigkeit
        if (Input.GetButton("Fire1"))
            if(bulletSize < 3) bulletSize = bulletSize + 0.1f;

        //beim Loslassen abfeuern
        if (Input.GetButtonUp("Fire1"))
            Fire();
      
          
        //nach links oder rechts drehen
        //rotation = Input.GetAxis("Horizontal");
        //rotation = (rotation * Time.deltaTime) * moveSpeed;
        //transform.Rotate(0, rotation, 0);
        //anzeigen
        //ammoLabel.text = "Munition: " + ammo.ToString();
        //bulletSpeedLabel.text = "Geschwindigkeit: " + bulletSpeed.ToString();
    }
}
