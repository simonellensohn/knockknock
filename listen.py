#!/usr/bin/env python3
import time
import requests
import numpy as np
import sounddevice as sd


bells = []
duration = 1
setsToCheckFor = 3
events = []
averages = []
baseUrl = 'https://knockknock.test/api'
accessToken = '1|F1UAuiR5XDfsqKq0nV96n7EDh2I3vsUUVusMkWAW'
requestHeaders = {
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + accessToken,
}
minRange = None
maxRange = None


def fetch_bells():
    response = requests.get(baseUrl + '/bells', headers=requestHeaders)

    return response.json().get('data')


def set_volume_range():
    global minRange
    global maxRange

    for bell in bells:
        value = float(bell.get('threshold'))

        if (minRange == None or value < minRange):
            minRange = value

        if (maxRange == None or value > maxRange):
            maxRange = value


def audio_callback(indata, frames, time, status):
    volume_norm = np.linalg.norm(indata) * 10
    events.append(volume_norm)


def listen_for_doorbell():
    with sd.InputStream(callback=audio_callback):
        sd.sleep(int(duration * 1000))


def average_of_last_events():
    return sum(events) / len(events)


def post_ring(bellId, volume, events):
    requests.post(
        baseUrl + '/bells/' + str(bellId) + '/ring',
        json={ 'volume': volume, 'events': events },
        headers=requestHeaders,
    )


def set_is_fluctuating(volumes):
    isFluctuating = False
    previousValue = None

    for volume in volumes:
        isFluctuating = previousValue and abs(previousValue - volume) > 3
        previousValue = volume

    return isFluctuating


bells = fetch_bells()
bells.sort(key=lambda bell: float(bell.get('threshold')), reverse=True)
set_volume_range()


while True:
    listen_for_doorbell()

    if (len(averages) >= setsToCheckFor):
        averages.pop(0)

    volume = average_of_last_events()
    averages.append(volume)
    average = np.average(averages)
    events = []

    if (average >= minRange and not set_is_fluctuating(averages)):
        bell = [bell for bell in bells if float(bell.get('threshold')) <= average][0]

        post_ring(bell.get('id'), average, averages)

        averages = []

        time.sleep(5)
