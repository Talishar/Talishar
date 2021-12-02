using UnityEngine;
using System.Collections;

public class Orbitplayer2 : MonoBehaviour
{

    public float turnSpeed = 5.0f;
    public GameObject player;

    private Transform playerTransform;
    private Vector3 offset;
    public float yOffset = 10.0f;
    public float zOffset = 10.0f;

    void Start()
    {
        playerTransform = player.transform;
        offset = new Vector3(playerTransform.position.x, playerTransform.position.y + yOffset, playerTransform.position.z + zOffset);
    }

    void FixedUpdate()
    {
        offset = Quaternion.AngleAxis(Input.GetAxis("Mouse X") * turnSpeed, Vector3.up) * offset;
        transform.position = playerTransform.position + offset;
        transform.LookAt(playerTransform.position);
    }
}