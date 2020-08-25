import pandas as pd
import os
from sklearn import datasets
from sklearn.model_selection import train_test_split

iris = datasets.load_iris()

print(os.listdir('public'))

data = pd.read_csv('https://query1.finance.yahoo.com/v7/finance/download/MAPI.JK?period1=1503273600&period2=1597968000&interval=1d&events=history')

y = data.Close
X = data.drop('Adj Close', axis=1)

X_train, X_test, y_train, y_test = train_test_split(X, y,test_size=0.2, shuffle=False)

X_train.to_csv('data_train.csv', index=False,header=True) 

X_test.to_csv('data_test.csv', index=False,header=True)
