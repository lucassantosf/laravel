"""
 
Done at: 2026-10-29

824. Goat Latin

"""

class Solution:

    def toGoatLatin_v1(self, sentence: str) -> str:

        words = sentence.split(" ")
        total = len(words)
        response = ''
        end = 'a'

        for i,word in enumerate(words):

            letter = word[0]
            vowels = ["a","e","i","o","u"]

            if letter.lower() in vowels:
                response += f"{word}ma"
            else:
                _sub = word[1:]
                response += f"{_sub}{letter}ma"
            
            response += f"{end}"

            if(i != total-1):
                response += " "

            end += "a"

        return response.strip()

    def toGoatLatin(self, sentence: str) -> str:
        vowels = set("aeiouAEIOU")
        result = []

        for i, word in enumerate(sentence.split(), 1):
            if word[0] not in vowels:
                word = word[1:] + word[0]
            result.append(f"{word}ma{'a' * i}")

        return " ".join(result)
    
solution = Solution()
sentence = "I speak Goat Latin"
sentence2 = "The quick brown fox jumped over the lazy dog"

result = solution.toGoatLatin(sentence)
result2 = solution.toGoatLatin(sentence2)

print(result)
print(result2)