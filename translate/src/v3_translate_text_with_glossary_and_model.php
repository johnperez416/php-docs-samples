<?php
/*
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Translate;

// [START translate_v3_translate_text_with_glossary_and_model]
use Google\Cloud\Translate\V3\TranslateTextGlossaryConfig;
use Google\Cloud\Translate\V3\TranslationServiceClient;

/**
 * @param string $modelId       Your model ID.
 * @param string $glossaryId    Your glossary ID.
 * @param string $text          The text to translate.
 * @param string $targetLanguage    Language to translate to.
 * @param string $sourceLanguage    Language of the source.
 * @param string $projectId     Your Google Cloud project ID.
 * @param string $location      Project location (e.g. us-central1)
 */
function v3_translate_text_with_glossary_and_model(
    string $modelId,
    string $glossaryId,
    string $text,
    string $targetLanguage,
    string $sourceLanguage,
    string $projectId,
    string $location
): void {
    $translationServiceClient = new TranslationServiceClient();

    /** Uncomment and populate these variables in your code */
    // $modelId = '[MODEL ID]';
    // $glossaryId = '[YOUR_GLOSSARY_ID]';
    // $text = 'Hello, world!';
    // $targetLanguage = 'fr';
    // $sourceLanguage = 'en';
    // $projectId = '[Google Cloud Project ID]';
    // $location = 'global';
    $glossaryPath = $translationServiceClient->glossaryName(
        $projectId,
        $location,
        $glossaryId
    );
    $modelPath = sprintf(
        'projects/%s/locations/%s/models/%s',
        $projectId,
        $location,
        $modelId
    );
    $contents = [$text];
    $glossaryConfig = new TranslateTextGlossaryConfig();
    $glossaryConfig->setGlossary($glossaryPath);
    $formattedParent = $translationServiceClient->locationName(
        $projectId,
        $location
    );

    // Optional. Can be "text/plain" or "text/html".
    $mimeType = 'text/plain';

    try {
        $response = $translationServiceClient->translateText(
            $contents,
            $targetLanguage,
            $formattedParent,
            [
                'model' => $modelPath,
                'glossaryConfig' => $glossaryConfig,
                'sourceLanguageCode' => $sourceLanguage,
                'mimeType' => $mimeType
            ]
        );
        // Display the translation for each input text provided
        foreach ($response->getGlossaryTranslations() as $translation) {
            printf('Translated text: %s' . PHP_EOL, $translation->getTranslatedText());
        }
    } finally {
        $translationServiceClient->close();
    }
}
// [END translate_v3_translate_text_with_glossary_and_model]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
