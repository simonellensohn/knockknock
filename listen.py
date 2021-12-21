#!/usr/bin/env python3
import numpy as np
import sounddevice as sd
import requests

bells = []
volumeThresholds = {}
duration = 2
setsToCheckFor = 2
events = []
averages = []
baseUrl = 'https://knockknock.test/api'
accessToken = '1|2g0Hlkf6zjEQCJr5TkEb3VSnQ3HGzJKig9LB7We4'
requestHeaders = {'Authorization': 'Bearer ' + accessToken}


def fetch_bells():
    global bells
    response = requests.get(baseUrl + '/bells', headers=requestHeaders, verify=False)
    bells = response.json().get('data')


def set_threshold_map():
    for bell in bells:
        volumeThresholds[float(bell.get('threshold'))] = bell.get('id')


def audio_callback(indata, frames, time, status):
    volume_norm = np.linalg.norm(indata) * 10
    events.append(volume_norm)


def listen_for_doorbell():
    with sd.InputStream(callback=audio_callback):
        sd.sleep(int(duration * 1000))


def average_of_last_events():
    return sum(events) / len(events)

def post_ring(bellId, volume):
    requests.post(
        baseUrl + '/bells/' + str(bellId) + '/ring',
        data={ 'volume': volume },
        headers=requestHeaders,
        verify=False
    )

fetch_bells()
set_threshold_map()

while True:
    listen_for_doorbell()

    if (len(averages) >= setsToCheckFor):
        averages.pop(0)

    average = average_of_last_events()

    # False positive, start from scratch
    if (average > max(volumeThresholds.keys())):
        averages = []
        events = []
        continue

    averages.append(average)
    events = []
    average = np.sum(averages)

    if (any(average >= volume for volume in volumeThresholds)):
        matchingBells = filter(lambda bell: bell.treshhold >= average, bells)
        bellId = volumeThresholds[min(volumeThresholds.keys())]

        post_ring(bellId, average)

        averages = []
