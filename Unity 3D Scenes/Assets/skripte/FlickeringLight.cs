using UnityEngine;
using System.Collections;

public class FlickeringLight : MonoBehaviour {

	public enum WaveForm {sin, tri, sqr, saw, inv, noise}; 
	public WaveForm waveform = WaveForm.sin;   

	public float baseStart = 0.0f; // start 
	public float amplitude = 1.0f; // amplitude of the wave
	public float phase = 0.0f; // start point inside on wave cycle
	public float frequency = 0.5f; // cycle frequency per second

	// Keep a copy of the original color
	private Color originalColor; 
	private Light light;

	// Store the original color
	void Start () {   
		light = GetComponent<Light>(); 
		originalColor = light.color;
	}

	void Update () {  
		light.color = originalColor * (EvalWave());
	}

	float EvalWave () { 
		float x = (Time.time + phase) * frequency;
		float y ;
		x = x - Mathf.Floor(x); // normalized value (0..1)

		if (waveform == WaveForm.sin) {

			y = Mathf.Sin(x * 2 * Mathf.PI);
		} 
		else if (waveform == WaveForm.tri) {

			if (x < 0.5f)
				y = 4.0f * x - 1.0f;
			else
				y = -4.0f * x + 3.0f;  
		}      
		else if (waveform == WaveForm.sqr) {

			if (x < 0.5f)
				y = 1.0f;
			else
				y = -1.0f;  
		}      
		else if (waveform == WaveForm.saw) {

			y = x;
		}      
		else if (waveform == WaveForm.inv) {

			y = 1.0f - x;
		}      
		else if (waveform == WaveForm.noise) { 

			y = 1f - (Random.value * 2);
		}
		else { 
			y = 1.0f;
		}          
		return (y * amplitude) + baseStart;    
	}
}

