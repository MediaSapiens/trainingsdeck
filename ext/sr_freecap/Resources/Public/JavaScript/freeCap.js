/***************************************************************
*  Copyright notice
*
*  (c) 2007-2013 Stanislas Rolland <typo3(arobas)sjbr.ca>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/*
 * Javascript functions for TYPO3 extension freeCap CAPTCHA (sr_freecap)
 *
 */
(function () {
	SrFreecap = {

		/*
		 * Loads a new freeCap image
		 *
		 * @param string id: identifier used to uniiquely identify the image
		 * @param string noImageMessage: message to be displayed if the image element cannot be found
		 * @return void
		 */
		newImage: function (id, noImageMessage) {
			if (document.getElementById) {
					// extract image name from image source (i.e. cut off ?randomness)
				var theImage = document.getElementById('tx_srfreecap_captcha_image_' + id);
				var parts = theImage.src.split('&set');
				theImage.src = parts[0] + '&set=' + Math.round(Math.random()*100000);
			} else {
				alert(noImageMessage ? noImageMessage : 'Sorry, we cannot autoreload a new image. Submit the form and a new image will be loaded.');
			}
		},
		
		/*
		 * Plays the audio captcha
		 *
		 * @param string id: identifier used to uniquely identify the wav file
		 * @param string wavURL: url of the wave file generating script
		 * @param string noPlayMessage: message to be displayed if the audio file cannot be rendered
		 * @return void
		 *
		 * Note: In order for this to work with IE8, [SYS][cookieDomain] must be set using the TYPO3 Install Tool
		 */
		playCaptcha: function (id, wavURL, noPlayMessage) {
			if (document.getElementById) {
				var theAudio = document.getElementById('tx_srfreecap_captcha_playAudio_' + id);
				var url = wavURL + '&set=' + Math.round(Math.random()*100000);
				while (theAudio.firstChild) {
					theAudio.removeChild(theAudio.firstChild);
				}
				var audioElement = document.createElement('audio');
				if (audioElement.canPlayType && (audioElement.canPlayType('audio/x-wav') === 'maybe' || audioElement.canPlayType('audio/x-wav') === 'probably') && !window.opera) {
					audioElement.setAttribute('id', 'tx_srfreecap_captcha_playAudio_audio' + id);
					audioElement.setAttribute('src', url);
					theAudio.appendChild(audioElement);
					audioElement.load();
					audioElement.play();
				} else {
					// In IE, use the default player for audio/x-wav, probably Windows Media Player
					var objectElement = document.createElement('object');
					objectElement.setAttribute('id', 'tx_srfreecap_captcha_playAudio_object' + id);
					if (document.all && !document.querySelector && !document.addEventListener) {
						// IE7 only
						objectElement.setAttribute('type', 'audio/x-wav');
						objectElement.setAttribute('filename', url);
					} else {
						objectElement.setAttribute('data', url);
						if (document.all && !document.addEventListener) {
							// IE8 only
							objectElement.setAttribute('type', 'audio/x-wav');
						}
					}
					theAudio.appendChild(objectElement);
					objectElement.style.height = 0;
					objectElement.style.width = 0;
					var parameters = {
						autoplay: 'true',
						autostart: 'true',
						controller: 'false',
						showcontrols: 'false'
					};
					for (var parameter in parameters) {
						if (parameters.hasOwnProperty(parameter)) {
							var paramElement = document.createElement('param');
							paramElement.setAttribute('name', parameter);
							paramElement.setAttribute('value', parameters[parameter]);
							paramElement = objectElement.appendChild(paramElement);
						}
					}
					$altHtml = '<a style="display:inline-block; margin-left: 5px; width: 200px;" href="' + url + '">' + (noPlayMessage ? noPlayMessage : 'Sorry, we cannot play the word of the image.') + '</a>';
					if (document.addEventListener) {
						// In IE9 and Opera
						objectElement.innerHTML = $altHtml;
					} else {
						objectElement.altHtml = $altHtml;
					}
				}
			} else {
				alert(noPlayMessage ? noPlayMessage : 'Sorry, we cannot play the word of the image.');
			}
		}
	}
})();
