﻿using System.Collections;
using System.IO;

using UnityEngine;

public class PlayerController : MonoBehaviour
{
    private float movementDuration = 2.0f;
    private WaitForSeconds waitBeforeMoving = new WaitForSeconds(2f);
    private Vector3[] path = new Vector3[20];
    private string filepath = null;

    private void Start()
    {
        StartCoroutine(MainRoutine());
        filepath = Application.dataPath + "/Player.txt";
        File.WriteAllText(filepath, "The player blob visited these random coordinates: \n\n");
    }

    private IEnumerator MainRoutine()
    {
        //generate new path:
        for (int i = 0; i < path.Length; i++)
        {
            float timer = 0.0f;
            Vector3 startPos = transform.position;
            float x = RandomNum(timer);
            float y = RandomNum(x);
            float z = RandomNum(y);
            path[i] = new Vector3(x, y, z);
        }

        //traverse path:
        for (int i = 0; i < path.Length; i++)
        {
            float timer = 0.0f;
            Vector3 startPos = transform.position;
            while (timer < movementDuration)
            {
                timer += Time.deltaTime;
                float t = timer / movementDuration;
                //t = t * t * t * (t * (6f * t - 15f) + 10f);
                transform.position = Vector3.Lerp(startPos, path[i], t);
                yield return null;
            }
            yield return waitBeforeMoving;
        }

        //print path:
        PrintPoints();
    }

    private void PrintPoints()
    {
       
        foreach (Vector3 vector in path)
        {
            File.AppendAllText(filepath, string.Format("{0}\n\n", JsonUtility.ToJson(vector)));
        }
    }

    private float RandomNum(float lastRandNum)
    {
        //Random value range can be changed in the future if necessary
        float randNum = Random.Range(-10.0f, 10.0f);
        return System.Math.Abs(randNum - lastRandNum) < float.Epsilon ? RandomNum(randNum) : randNum;
    }

}
