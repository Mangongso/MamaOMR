## MamaOMR?
<p align="center">
<img src="https://github.com/Mangongso/MamaOMR/blob/ziman/Docs/Images/mamaomr.png?raw=true" align="center"/>
</p>
MamaOMR is a smart-learning applicaition for parants and students to homeschool in a self-directed way.
Users can easily make online and printable OMR sheets for any type of questionnaire books in the market. And they are readily able to convert their answering results into digitalized data.  

MamaOMR identifies students' understanding and weak points statistically through recoding, comparing and analyzing their answering results. And it can also easily make them locate their objective status among all the students who already answered the same questionnaire books. 

You can get the following main advantages with using MamaOMR, the learning analytics platform.   

## MamaOMR's three main advantages
<b> First, MamaOMR will analyze your weak points and advise you for better learning achievement.</b> You will be provided analysis report of weak points. You don't need to grade the questionnaire book by hand and you also don't need to spend your precious time making wrong answer notes.
 
<b>Second, smartchart will make you easily locate your status of learning performance by comparing yours to other students'.</b> If you don't take the nationwide mock test, you hardly have a chance to compare learning performance with your peer group.  MamaOMR visualizes performance comparison of all the students who already answered the same questionnaire book.
 
<b>Third, you can overcome your weakness very effectively through smart wrong answer notes and recommended questions by wrong-answered question intelligence.</b> Your wrong answer notes are automatically stored and classified on the basis of analysis tags. And you can access the notes with any digital device at any time. MamaOMR intelligently analyzes and classifies the wrong answers of all the students and recommend you optimized questions which you are most likely to answer wrong in the future.

## Quick Guide

<p align="center">
<img src="https://github.com/Mangongso/Docs/blob/gh-pages/images/mamaOMR_img_intro_en.png?raw=true?raw=true" align="center"/>
</p>

## Demo Site
[http://mamaomr.hanbnc.com](http://mamaomr.hanbnc.com/)

You can test after login with your SNS account

## MamaOMR movie on Youtube

[One minute introduce](https://youtu.be/lFkpcSV7C0w)

[MamaOMR Demo movie - Mobile](https://youtu.be/8WhAiRH__QA)

[MamaOMR Deme movie - Desktop](https://youtu.be/s6QPzFahH4E)

## MamaOMR's main functions
* Questionnaire book management by ISBN code
* Smart OMR which makes you easily digitalize answering results, analysis tags, right answers
* Real-time grading and weakness analysis report
* Auto scanning of captured OMR sheet with smartphones  
* Auto classification of wrong answer questions and auto creation of wrong answer notes
* Auto text extraction from mobile captured questions by OCR
* Parental monitoring feature of learning performance
* Provisioning REST API for 3rd-party applications

## Install Dependency
* **Platform** : Linux, PHP5.4+(with GD v2.1.1+,xmllib v2.9.3+), MySQL5+
* **API** : Naver(Kakao,Facebook) Auth API Key,Daum Book Search API Key
* **External Library(Apps)**: [OpenOMR - GNU2](https://github.com/henricavalcante/openomr), [tesseract ocr for php - Apache License V2](https://github.com/Mangongso/tesseract-ocr-for-php), [Tesseract - Apache License V2](https://github.com/tesseract-ocr/tesseract)
* **UI Library** : [UIkit 2.26.1 - MIT Lecense](http://getuikit.com), [Bootstrap v3.3.2 - MIT Lecense](http://getbootstrap.com), [Pure v0.6.0 - MIT Lecense](http://purecss.io/)
* **Javascript Library** : [jQuery v1.12.4 - MIT Lecense](http://www.jquery.org)

### External Library Detail
* **OpenOMR** : OMR reader
* **tesseract ocr for php** : Tesseract OCR PHP library
* **Tesseract** : grap text from incorrect question photo (you can change ocr.space REST API)

## Platform Design Document
* <a href="https://goo.gl/xBQRc0" target="_blank">Requirement Analysis</a>
* [Menu Structure and Wireframe](https://goo.gl/OzZ4uj)
* <a href="https://goo.gl/9dOUES" target="_blank">Storyboard</a>
* [System Architecture](System-Architecture)
* [Web Application Architecture](Web-Application-Architecture)
* [ERD](ERD)
* [Use Case Diagram](Use-Case-Diagram)
* [Class Diagram](Class-Diagram)
* [Sequence Diagram](Sequence-Diagram)

## Test Document
* [Test Results of Web Accessibility and Standard Coding](https://docs.google.com/spreadsheets/d/1tOyRe4xm3EYxLCktkxlwGlK-zBbk0hzO8ohoO4vh0OE/edit#gid=651159913)
* [Integrated Test Results](https://docs.google.com/spreadsheets/d/1tOyRe4xm3EYxLCktkxlwGlK-zBbk0hzO8ohoO4vh0OE/edit#gid=292313985)

## Other Document
* [User's manual](User-Manual)
* [Development Guide](Development-Document)
* <a href="http://mamaomr.hanbnc.com/docs/api" target="_blank">Application API</a>
* [REST API](Rest-API)

## Copyright and license

Code and documentation copyright 2016 the Mangongso Team. Code released under the MIT license. Docs released under Creative Commons.
