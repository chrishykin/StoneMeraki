# StoneMeraki
Experimentation with the Meraki API

This is a database schema and ingestion script that takes data from the Cisco Meraki Scanning API and puts it into a MySQL database.

The mySQL database is split in to 2no tables:
- Observations
- Visits

Observations includes all the raaw data from the v2 Scanning API platform.
Visits adds basic logic to say that "if I've seen this MAC address before, in a specified timeframe, I will treat this as one 'visit' and simply extend the end time and duration of that visit in the system". 

This is useful for trying to track not only the general swathes of raw data included in the radio environment, but looking at 'visit' behaviour as well. 

Once this ingest script is in place and running, pointing PowerBI Desktop at it makes for an easy way to get a macro view of your Meraki Environment.

Happy for any feedback/comments - hope it's useful

Chris Hykin
Stone Technologies
chris.hykin\@stonegroup.co.uk
