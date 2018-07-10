from hashlib import sha256
from time import time
from urllib.parse import urlparse
import requests
from flask import Flask, jsonify, request
import random
#Declaration of the Blockchain

class Blockchain(object):
    def __init__(self):
        self.chain= []
        self.vote_info = {}
        self.neighbours = [] #Sets are immutable, hence better than lists. Also in this case it is used to ensure a same address does not register twice
        self.create_genesis(previous_hash = 1,proof = 1)

    def validate_chain(self, chain):
        last_block = chain[0]
        index = 1
        while(index < len(chain)):
            block = chain[index]
            if block['previous_hash'] != self.hash(last_block):
                return False
            if self.validate_proof(last_block['proof'], block['proof']) == False:
                return False
            last_block = block
            index += 1
        return True

    def update_chain(self):
        neighbours = self.neighbours
        new_chain = []
        current_length = len(self.chain)
        for neighbour in neighbours:
            response = requests.get('http://'+neighbour+'/display_chain') #request for chain from the neighboring blocks
            if response.status_code == 200:
                neighbour_length = response.json()['length'] #find length of neighbor chain
                neighbour_chain = response.json()['chain'] # find the neighbor chain
                if(neighbour_length != current_length):
                    current_length+=neighbour_length
                    new_chain.append(neighbour_chain)   ####NEED TO CONVERT JSON TO DICT!!
        if len(new_chain)>0:
            original_chain=self.chain
            temporary_chain=[]
            for k in new_chain:
                for t in k:
                    if t not in self.chain: # find all the blocks that aren't in the present chain and add them
                        self.chain.append(t)
            return True
        return False

    def add_neighbour(self,address):
        self.neighbours.append(urlparse(address).netloc)    #Adds the network location of the input address into the set

    # checks if the complete binary tree represented by chararray[0:14] is a binary search tree.
    def check_for_bst(self, chararray):
        import time
        import hashlib
        i = 1
        j = 0
        testlen = 10
        while(1):
            starttime = time.time()
            while(1):
                code = hashlib.sha256(str(i).encode()).hexdigest()
                flag = 0
                for j in range(0, testlen):
                    print(code)
                    right = 2*j + 2;
                    left = 2*j + 1;
                    if (left >= testlen or right >= testlen):
                        break
                    if(ord(code[left]) > ord(code[j]) or ord(code[right]) < ord(code[j])):
                        flag = 1
                        break
                if(flag == 0):
                    print(code)
                    break
                i += 1
            finishtime = time.time()
            print(finishtime - starttime)
            break



    # need to make a harder proof of work
    def validate_proof(self, previous_proof,proof):
        #Function that validates the proof and checks if the current proof is correct
        predicted = str(previous_proof*proof).encode()
        predicted_hash = sha256(predicted).hexdigest()
        print(predicted_hash)

        return self.check_for_bst(predicted_hash[0:2])

    def proof_of_work(self,previous_proof):
        #Finds a proof such that when hashed with the previous proof gives a hash who's first two and last two characters are equal
        proof=0
        while(True):
            if(self.validate_proof(previous_proof,proof)==True):
                break
            proof+=1
        return proof

    def create_genesis(self,previous_hash,proof):
        #Creates a genesis block and adds it to the chain
        block={
            'index': 0,
            'time': time(),
            'vote_info': {'voter':'Genesis','UIDAI':'000000000000','vote':'Genesis'},
            'proof': proof,
            'previous_hash': previous_hash
        }
        self.chain.append(block)

    def create_block(self,previous_hash,proof):
        #Creates a new block and adds it to the chain
        block={
            'index':len(self.chain)+1,
            'time':time(),
            'vote_info':self.vote_info,
            'proof':proof,
            'previous_hash':previous_hash
        }
        self.vote_info={}
        self.chain.append(block)
        return block

    def create_vote(self,voter_name,aadhar_number,voted_for):
        #Adds a new vote to the existing list of vote
        self.vote_info.update({
            'voter':voter_name,
            'UIDAI':aadhar_number,
            'vote': voted_for #need to add logic to check if the candidate voted for has been registered previously on the server
        })
        return self.last_block["index"]+1

    @staticmethod
    def hash(block):
        #Hashes a block using SHA-256
        sorted_block=dict(sorted(block.items())) #Sort the block to maintain consistency
        sorted_block=str(sorted_block).encode()          #Encode block to be able to hash
        return sha256(sorted_block).hexdigest()

    @property
    def last_block(self):
        #Returns the last block of the current chain
        return self.chain[-1]
