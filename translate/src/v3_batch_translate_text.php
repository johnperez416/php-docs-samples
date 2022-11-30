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

// [START translate_v3_batch_translate_text]
use Google\Cloud\Translate\V3\GcsDestination;
use Google\Cloud\Translate\V3\GcsSource;
use Google\Cloud\Translate\V3\InputConfig;
use Google\Cloud\Translate\V3\OutputConfig;
use Google\Cloud\Translate\V3\TranslationServiceClient;

/**
 * @param string $inputUri      Path to to source input (e.g. "gs://cloud-samples-data/text.txt").
 * @param string $outputUri     Path to store results (e.g. "gs://YOUR_BUCKET_ID/results/").
 * @param string $projectId     Your Google Cloud project ID.
 * @param string $location      Project location (e.g. us-central1)
 * @param string $targetLanguage    Language to translate to.
 * @param string $sourceLanguage    Language of the source.
 */
function v3_batch_translate_text(
    string $inputUri,
    string $outputUri,
    string $projectId,
    string $location,
    string $targetLanguage,
    string $sourceLanguage
): void {
    $translationServiceClient = new TranslationServiceClient();

    $targetLanguageCodes = [$targetLanguage];
    $gcsSource = (new GcsSource())
        ->setInputUri($inputUri);

    // Optional. Can be "text/plain" or "text/html".
    $mimeType = 'text/plain';
    $inputConfigsElement = (new InputConfig())
        ->setGcsSource($gcsSource)
        ->setMimeType($mimeType);
    $inputConfigs = [$inputConfigsElement];
    $gcsDestination = (new GcsDestination())
        ->setOutputUriPrefix($outputUri);
    $outputConfig = (new OutputConfig())
        ->setGcsDestination($gcsDestination);
    $formattedParent = $translationServiceClient->locationName($projectId, $location);

    try {
        $operationResponse = $translationServiceClient->batchTranslateText(
            $formattedParent,
            $sourceLanguage,
            $targetLanguageCodes,
            $inputConfigs,
            $outputConfig
        );
        $operationResponse->pollUntilComplete();
        if ($operationResponse->operationSucceeded()) {
            $response = $operationResponse->getResult();
            printf('Total Characters: %s' . PHP_EOL, $response->getTotalCharacters());
            printf('Translated Characters: %s' . PHP_EOL, $response->getTranslatedCharacters());
        } else {
            $error = $operationResponse->getError();
            print($error->getMessage());
        }
    } finally {
        $translationServiceClient->close();
    }
}
// [END translate_v3_batch_translate_text]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
