using UnityEngine;
using System.Collections;

public class OrbitPlayer : MonoBehaviour
{

    public float turnSpeed = 5.0f;
    public GameObject player;

    private Transform playerTransform;
    private Vector3 offset;
    public float yOffset = 4.0f;
    public float zOffset = 5.0f;
    public float xOffset = 0f;

    float x = 1;

    void Start()
    {
        playerTransform = player.transform;
        offset = new Vector3(playerTransform.position.x + xOffset, playerTransform.position.y + yOffset, playerTransform.position.z + zOffset);
    }

    void FixedUpdate()
    {
       
        offset = Quaternion.AngleAxis((x * Time.deltaTime) * turnSpeed, Vector3.up) * offset;
        transform.position = playerTransform.position + offset;
        transform.LookAt(playerTransform.position);
    }
}