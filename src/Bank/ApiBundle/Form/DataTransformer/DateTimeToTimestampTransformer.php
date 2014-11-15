<?php
/**
 * User: asmisha
 * Date: 15.11.14
 * Time: 12:51
 */

namespace Bank\ApiBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeToTimestampTransformer implements DataTransformerInterface
{
	/**
	 * Transforms an object (DateTime) to a string (number).
	 *
	 * @param  \DateTime|null $dateTime
	 * @return string
	 */
	public function transform($dateTime)
	{
		if (null === $dateTime) {
			return '';
		}

		return $dateTime->getTimestamp();
	}

	/**
	 * Transforms a string (number) to an object (DateTime).
	 *
	 * @param  string $timeStamp
	 *
	 * @return \DateTime|null
	 *
	 * @throws TransformationFailedException if object (issue) is not found.
	 */
	public function reverseTransform($timeStamp)
	{
		if (!$timeStamp) {
			return null;
		}

		$dateTime = \DateTime::createFromFormat('U', $timeStamp);

		if (!$dateTime) {
			throw new TransformationFailedException(sprintf(
				'%s doesn\'t represent a valid timestamp',
				$timeStamp
			));
		}

		return $dateTime;
	}
}