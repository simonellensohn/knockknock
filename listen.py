#!/usr/bin/env python3
import numpy as np
import sounddevice as sd
import requests

volumeThreshold = 7
duration = 2
setsToCheckFor = 2
events = []
averages = []
url = 'https://knockknock.simonellensohn.com/api/bells/1/ring'
accessToken = 'jCdYlEjfgpKJUG07DEy0WEkE3CFUbuuEc4r67prv'


def audio_callback(indata, frames, time, status):
    volume_norm = np.linalg.norm(indata) * 10
    events.append(volume_norm)


def listen_for_doorbell():
    with sd.InputStream(callback=audio_callback):
        sd.sleep(int(duration * 1000))


def average_of_last_events():
    return sum(events) / len(events)

def post_ring():
    requests.post(url, headers={'Authorization': 'Bearer ' + accessToken})

# post_ring()y

while True:
    listen_for_doorbell()

    if (len(averages) >= setsToCheckFor):
        averages.pop(0)

    average = average_of_last_events()

    # False positive, start from scratch
    if (average > volumeThreshold):
        averages = []
        events = []
        continue

    averages.append(average)
    events = []

    if (np.sum(averages) >= volumeThreshold):
        post_ring()
        averages = []


