<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Github;

use Elewant\Webapp\DomainModel\Contributor\Contributor;
use Elewant\Webapp\DomainModel\Contributor\ContributorList;
use Http\Client\Exception as HttpException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class GithubContributorList implements ContributorList
{
    protected string $username;

    protected string $repository;

    /**
     * @var string[]
     */
    protected array $blacklist;

    private MessageFactory $requestFactory;

    protected HttpClient $client;

    /**
     * @param string $username
     * @param string $repository
     * @param MessageFactory $requestFactory
     * @param HttpClient $client
     * @param string[] $blacklist
     */
    public function __construct(
        string $username,
        string $repository,
        MessageFactory $requestFactory,
        HttpClient $client,
        array $blacklist = []
    )
    {
        $this->username = $username;
        $this->repository = $repository;

        $this->requestFactory = $requestFactory;
        $this->client = $client;
        $this->blacklist = $blacklist;
    }

    /**
     * @return Contributor[]
     * @throws HttpException
     */
    public function allContributors(): array
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            'https://api.github.com/repos/' . $this->username . '/' . $this->repository . '/contributors'
        );
        $response = $this->client->sendRequest($request);

        $contributors = [];

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $responseData = json_decode((string) $response->getBody(), true);

            foreach ($responseData as $contributorData) {
                if (in_array($contributorData['login'], $this->blacklist)) {
                    continue;
                }

                $contributors[] = GithubContributor::fromGithubApiCall($contributorData);
            }
        }

        return $contributors;
    }
}
