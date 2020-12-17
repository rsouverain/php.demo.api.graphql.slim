#!/bin/sh

trap : TERM INT
tail -f /dev/null & wait
# Ah, ha, ha, ha, stayin' alive...