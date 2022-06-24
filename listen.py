#!/usr/bin/env python3
import time
import requests
import numpy as np
import sounddevice as sd


bell = {id: 1}
ringDuration = 3000
volumeSetDuration = 250
sleepDurationAfterRing = 10000
volumeSetsToCheck = np.round(ringDuration / volumeSetDuration)
events = []
averages = []
baseUrl = 'https://knockknock.test/api'
accessToken = ''
requestHeaders = {
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + accessToken,
}


def fetch_bell():
    response = requests.get(baseUrl + '/bells' +
                            bell.get('id'), headers=requestHeaders)

    bell = response.json().get('data')


def audio_callback(indata, frames, time, status):
    volume_norm = np.linalg.norm(indata) * 10
    events.append(volume_norm)


def listen_for_doorbell():
    with sd.InputStream(callback=audio_callback):
        sd.sleep(int(volumeSetDuration))


def average_of_last_events():
    return sum(events) / len(events)


def post_ring(bellId, volume, events):
    requests.post(
        baseUrl + '/bells/' + str(bellId) + '/ring',
        json={'volume': volume, 'events': events},
        headers=requestHeaders,
    )


def set_is_fluctuating(volumes):
    lowestVolume = min(volumes)
    highestVolume = max(volumes)

    if abs(highestVolume - lowestVolume) >= 3:
        return True

    previousValue = None

    for volume in volumes:
        isFluctuating = previousValue is not None and abs(
            previousValue - volume) > 1.5

        if isFluctuating:
            return True

        previousValue = volume

    return False


fetch_bell()

minRange = bell.get('min_volume')
maxRange = bell.get('maxn_volume')

while True:
    listen_for_doorbell()

    if (len(averages) >= volumeSetsToCheck):
        averages.pop(0)

    volume = average_of_last_events()

    # Reset the averages if the volume is outside a threshold where
    # a.) nothing is happening or
    # b.) the volume is lower or higher than any tone of the bell
    if (volume < minRange or volume > maxRange):
        averages = []
        events = []
        continue

    averages.append(volume)

    if (len(averages) < volumeSetsToCheck):
        continue

    average = np.average(averages)
    events = []

    if (average >= minRange and not set_is_fluctuating(averages)):
        post_ring(bell.get('id'), average, averages)

        averages = []

        time.sleep(sleepDurationAfterRing / 1000)
