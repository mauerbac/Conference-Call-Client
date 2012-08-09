#In-browser Conference Call: Powered by Twilio Client

This app is a use case of [Twilio Client](http://www.twilio.com/docs/howto/twilio-client-browser-conference-call) with a few additional features. 
Here members can either call in to the conference or join via the web browser, either way all 
members are connected to the same conference call. This app alsos lists all active
participants in the conference as well as differentiating between a browser call or an actual phone. 
This app also incorporates Google Oauth by only allowing access to members of your company.  

## Usage 

Company members call the conference line or call in via the web browser. 

![Example of it
working](https://raw.github.com/mauerbac/Conference-Call-Client/master/images/img1.png)

![Example of it
working](https://raw.github.com/mauerbac/Conference-Call-Client/master/images/img2.png)


## Installation

Step-by-step on how to deploy, configure and develop this app.

### Create Credentials

1) Create a ClientId, ClientSecret, RedirectUri and Developer Key from your [Google Oauth Dashboard](https://code.google.com/apis/console).

2) Create a new TwiML App. In the Voice Request URL put the location of conf.xml

3) Add a phone number to your Twilio account. (This will be your conference number). In the Voice Request URl put the location of conf.xml

##Configuration 

1)

2) Edit conf.xml. Put your conference name in between the <Conference> tags

3) Edit constants.php. Add all Credentials

4) Edit conference.php. In the if-else block add respective emails with client names
        
5)Edit pullinfo.php. In the if-else block add respective phone numbers with client names 


