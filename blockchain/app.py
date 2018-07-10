from hashlib import sha256
from time import time
from urllib.parse import urlparse
import requests
from flask import Flask, jsonify, request
import random
from blockchain import Blockchain

#Below is the code for our server

app = Flask(__name__)

# Instantiate the Blockchain
blockchain = Blockchain()

def check():
    new_chain=blockchain.update_chain()
    if new_chain:
        response={
            'message':'Chain has been udpdated',
            'new chain':blockchain.chain
        }
    else:
        response={
            'message':'Chain is up to date',
            'chain':blockchain.chain
        }
    return jsonify(response), 200

def transmit_vote(vote):
    neighbours=blockchain.neighbours
    for neighbour in neighbours:
        response=requests.post('http://'+neighbour+'/new_vote',data=vote)
        print('Vote transmitted')
        if response.status_code==400:
            print(response)

@app.route('/display_chain', methods=['GET'])
def full_chain():
    #check()
    response = {
        'chain': blockchain.chain,
        'length': len(blockchain.chain),
    }
    return jsonify(response), 200

@app.route('/new_vote', methods=['POST'])
def new_vote():
    check()
    values = request.get_json()
    print(values)

    # Check that the required fields are in the POST'ed data
    required = ['voter', 'UIDAI', 'vote']
    if not all(k in values for k in required):
        return 'Missing values', 400

    if len(values['UIDAI'])!=12:
        return 'Invalid UIDAI',400
    for block in blockchain.chain:
        if values['UIDAI'] == block['vote_info']['UIDAI']:
            return 'You have already voted for '+block['vote_info']['vote'], 400

    transmit_vote(dict(values))
    index = blockchain.create_vote(values['voter'], values['UIDAI'], values['vote'])
    response = {'message': 'Your vote has been registered'}
    # We run the proof of work algorithm to get the next proof...
    last_block = blockchain.last_block
    last_proof = last_block['proof']    
    proof = blockchain.proof_of_work(last_proof)
    # Forge the new Block by adding it to the chain
    previous_hash = blockchain.hash(last_block)
    block = blockchain.create_block(previous_hash,proof)
    # Create a new vote
    response = {
        'message': "New Vote Block Forged",
        'index': block['index'],
        'vote_info': block['vote_info'],
        'proof': block['proof'],
        'previous_hash': block['previous_hash'],
    }
    return jsonify(response), 201

@app.route('/register_node', methods=['POST'])
def register_node():
    registeration_request=request.get_json()
    neighbours=registeration_request.get('neighbours')
    if neighbours is None:
        return 'Invalid node URL supplied',400
    for neighbour in neighbours:
        blockchain.add_neighbour(neighbour)
    response={
        'message':'New nodes added successfully!',
        'neighbours':list(blockchain.neighbours)
    }
    return jsonify(response), 201

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=int(input()))
