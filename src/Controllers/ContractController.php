<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\ContractService;

class ContractController
{
    private ContractService $contractService;

    public function __construct()
    {
        $this->contractService = new ContractService();
    }

    public function listContracts(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $filters = [
            'amount_min' => $queryParams['amount_min'] ?? null,
        ];

        $sortColumn = $queryParams['sort'] ?? 'id';
        $sortOrder = $queryParams['order'] ?? 'asc';

        $contracts = $this->contractService->getContracts($filters, $sortColumn, $sortOrder);

        $html = "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Contracts</title>
            <link rel='stylesheet' href='./style.css'>
        </head>
        <body>
        <h1>Contracts</h1>";

        $html .= "<form method='GET'>
            <label for='amount_min'>Minimum Amount:</label>
            <input type='number' id='amount_min' name='amount_min' value='{$filters['amount_min']}'>
            <button type='submit'>Filter</button>
        </form>";

        if (empty($contracts)) {
            $html .= "<p>No contracts found.</p>";
        } else {
            $html .= "<table>
                <thead>
                    <tr>
                        <th><a href='?sort=id&order=asc'>ID</a></th>
                        <th><a href='?sort=entrepreneur_name&order=asc'>Entrepreneur</a></th>
                        <th><a href='?sort=nip&order=asc'>NIP</a></th>
                        <th><a href='?sort=amount&order=asc'>Amount</a></th>
                    </tr>
                </thead>
                <tbody>";

            foreach ($contracts as $contract) {
                $html .= "<tr>
                    <td>{$contract['id']}</td>
                    <td>{$contract['entrepreneur_name']}</td>
                    <td>{$contract['nip']}</td>
                    <td>{$contract['amount']}</td>
                </tr>";
            }

            $html .= "</tbody></table>";
        }

        $html .= "</body></html>";

        $response->getBody()->write($html);
        return $response;
    }
}