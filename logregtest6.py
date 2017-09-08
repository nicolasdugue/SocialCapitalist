from numpy import loadtxt, zeros, log
import numpy as np
from sklearn import preprocessing, cross_validation, feature_selection
from sklearn.linear_model import LogisticRegression
from pymongo import MongoClient
import datetime
from sys import argv

# L=range(2,22)
# L = [2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20, 21]
# L=[2,3,4] #counts
# L=[5,6,7] #out in bi
# L=[8,9,10,11] #tweet content
# L=[12,13,14] #tweet caracteristics
# L=[16,17,18,19,20,21] #sources
# L=[8,10,14] # 3 best
# L=[21]

if len(argv) > 2:
    neg = loadtxt(argv[1])
    pos = loadtxt(argv[2])
else:
    neg = loadtxt('datafinal/neg')
    pos = loadtxt('datafinal/pos')

negs = neg[:, 1:]
poss = pos[:, 1:]

# on retire les features 5 et 13
x = np.delete(np.vstack((negs, poss)), [5, 13], 1)
y = np.hstack((np.zeros(negs.shape[0]), np.ones(poss.shape[0])))

# negs = neg[:, 1:]
# poss = pos[:, 1:]

# X_te0 = np.delete(np.vstack((negs, poss)), [5, 13], 1)
# y_te = np.hstack((np.zeros(negs.shape[0]), np.ones(poss.shape[0])))

X_tr0, X_te0, y_tr, y_te = cross_validation.train_test_split(
        x, y, test_size=0.3, random_state=0)


features = ['statuses', 'listed', 'favorites',
            'friends', 'followers', 'avg_length',
            'avg_hashtags', 'avg_url', 'avg_mentions',
            'avg_retweets', 'percent_retweets',
            'avg_retweeted', 'follow', 'management',
            'web', 'automatic', 'tierces', 'devices']

client = MongoClient()

coll_feats = client['twitter']['feats']
coll_coef = client['twitter']['coefs']

users = coll_feats.find()

# 3 -
for user in users:
        vals = user['vals']
        if(vals['casoc'] != 2):
                row = np.array([])

                for i in range(0, X_tr0.shape[1]):
                        row = np.hstack((row, vals[features[i]]))

                X_tr0 = np.vstack((X_tr0, row))
                y_tr = np.hstack((y_tr, vals['casoc']))


n_tr, m0 = X_tr0.shape
n_te = X_te0.shape[0]

m = m0+(m0*(m0+1))/2+1

# L=[0,1,2,3,4,5]
# X_tr0[L,:]=log(X_tr0[L,:]+1)
# X_te0[L,:]=log(X_te0[L,:]+1)

X_tr = zeros((n_tr, m))
X_te = zeros((n_te, m))
X_tr[:, :m0] = log(1 + X_tr0)
X_te[:, :m0] = log(1 + X_te0)

p = m0

for i in range(m0):
    for j in range(i, m0):
        X_tr[:, p] = X_tr[:, i] * X_tr[:, j]
        X_te[:, p] = X_te[:, i] * X_te[:, j]
        p += 1

scaler = preprocessing.StandardScaler().fit(X_tr)
X_tr = scaler.transform(X_tr)
X_te = scaler.transform(X_te)

X_tr[:, p] = 1
X_te[:, p] = 1

chi2_result = feature_selection.univariate_selection.chi2(X_tr0, y_tr)

print('chi2', chi2_result)

# print("p,m=", p, m)

clf = LogisticRegression(fit_intercept=False,
                         tol=1e-10,
                         penalty='l2',
                         class_weight='auto')

clf.fit(X_tr, y_tr)

y_tr_pr = clf.predict(X_tr)
y_te_pr = clf.predict(X_te)

tp, tn, n, p = 0., 0., 0., 0.

for i in range(len(y_tr)):
    if (y_tr[i] == 1):
        p += 1.
        if y_tr_pr[i] == 1:
            tp += 1.
    else:
        n += 1.
        if y_tr_pr[i] == 0:
            tn += 1.

tAc = (tp+tn)/(p+n)*100
tSe = tp/p*100
tSp = tn/n*100
tFs = 2*tSe*tSp/(tSe+tSp)

print('Train')
print('Accuracy:', tAc, '%')
print('Sensitivity:', tSe, '%')
print('Specificity:', tSp, '%')
print('F-score:', tFs, '%')

tp, tn, n, p = 0., 0., 0., 0.
for i in range(len(y_te)):
    if y_te[i] == 1:
        p += 1.
        if y_te_pr[i] == 1:
            tp += 1.
    else:
        n += 1.
        if y_te_pr[i] == 0:
            tn += 1.

Ac = (tp+tn)/(p+n)*100
Se = tp/p*100
Sp = tn/n*100
Fs = 2*Se*Sp/(Se+Sp)

print('Test')
print('Accuracy:', Ac, '%')
print('Sensitivity:', Se, '%')
print('Specificity:', Sp, '%')
print('F-score:', Fs, '%')


MEAN = scaler.mean_
STD = scaler.std_
COEF = clf.coef_[0]

n = len(COEF)
print(n, n == len(STD))

coefs = clf.coef_[0]

coll_coef.insert({
    'features': features[:m0],
    'stds': scaler.std_.tolist(),
    'means': scaler.mean_.tolist(),
    'coefs': coefs.tolist(),
    'date': datetime.datetime.utcnow(),
    'active': 0,
    'accuracy': Ac,
    'sensitivity': Se,
    'specificity': Sp,
    'f-score': Fs,
    'train_accuracy': tAc,
    'train_sensitivity': tSe,
    'train_specificity': tSp,
    'train_f-score': tFs
})
