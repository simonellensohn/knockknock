#!/usr/bin/env python3
import numpy as np
import sounddevice as sd

duration = 2
events = {}

def audio_callback(indata, frames, time, status):
  currentTime = time.currentTime
  volume_norm = np.linalg.norm(indata) * 10
  events[currentTime] = volume_norm;

def listen_for_doorbell():
    with sd.InputStream(callback=audio_callback):
        sd.sleep(int(duration * 1000))

def average_of_last_seconds():
    return sum(events.values()) / len(events)

while True:
    listen_for_doorbell()
    average = average_of_last_seconds()
    print(average)
    events = {}
