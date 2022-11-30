<?php
/**
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/storage/README.md
 */

namespace Google\Cloud\Samples\Storage;

# [START storage_copy_file_archived_generation]
use Google\Cloud\Storage\StorageClient;

/**
 * Copy archived generation of a given object to a new object.
 *
 * @param string $bucketName The name of your Cloud Storage bucket.
 *        (e.g. 'my-bucket')
 * @param string $objectToCopy The name of the object to copy.
 *        (e.g. 'my-object')
 * @param string $generationToCopy The generation of the object to copy.
 *        (e.g. 1579287380533984)
 * @param string $newObjectName The name of the target object.
 *        (e.g. 'my-object-1579287380533984')
 */
function copy_file_archived_generation(string $bucketName, string $objectToCopy, string $generationToCopy, string $newObjectName): void
{
    $storage = new StorageClient();
    $bucket = $storage->bucket($bucketName);

    $object = $bucket->object($objectToCopy, [
        'generation' => $generationToCopy,
    ]);

    $object->copy($bucket, [
        'name' => $newObjectName,
    ]);

    printf(
        'Generation %s of object %s in bucket %s was copied to %s',
        $generationToCopy,
        $objectToCopy,
        $bucketName,
        $newObjectName
    );
}
# [END storage_copy_file_archived_generation]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
