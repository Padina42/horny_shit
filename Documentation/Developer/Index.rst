.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer:

Developer Corner
================

You can install the plugin on a page.
The plugin show some examples to you, how to use the viewhelper.
The plugin-text is written in german language.
The f:alias-viewhelper in the plugin simulate the normal data-objects in a normal fluid-template.

You find the mind-map-descriptin of the configuration-array in the folder */typo3conf/ext/horny_shit/Documentation/Appendix/Configuration.mm*.





Remark
------
The main class of the svt-viewhelper is svt-service.
The class get the dynamic content in a structure configuration array and the string of the pattern-svg.
The viewhelper will valitade the syntax of the configuration array.
If the configuration fails the syntax test, the viewhelper won't work.
The viewhelper allow various maniüpulation with the methods of string-Search-replace-operatioons and with the method of XML-Manipulations.
The class gives back the svg-coded String after the manipulation.

The class and its associated classes are mainly indepent to TYPO3. Only the translation service of typo3 is used.
The viewhelper itself mange the transfer of datas form the fluid into the svt-classes and back.
