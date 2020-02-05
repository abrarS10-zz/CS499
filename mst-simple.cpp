#include <cstdio>
#include <vector>
#include <algorithm>
#include <cassert>
#include <climits>
using namespace std;

//a vector implementation of the Union-Find ADT
class union_find
{
	private: vector<int> p,root;
	public: void init (int n) {
		p.resize(n);
		root.assign(n, 0);
		for (int i = 0; i<n; i++)
			p[i] = i;
	}

	int find(int k) {
		return k == p[k] ? k : (p[k] = find(p[k]));
	}

	int dstunion (int a, int b) {
		a = find(a);
		b = find(b);
		if (a ==b)
			return 0;
		if (root[a] > root[b]) 
			p[b] = a;
		else {
			if (root[a] == root[b]) root[b]++;
		}
		
		return 1;
	}
};

//representation of weighted graph edges
struct GraphEdge {
	int x,y,w;

	void readinput() {
		scanf("%d%d%d", &x, &y, &w);
	}

	bool operator<(const GraphEdge &e) const {
		return w < e.w;
	}

};

//constructing MST with Kruskal's algorithm
//and calculating the cost of edges NOT in MST

int main() {
	freopen("mst-simple.in", "r", stdin);
	while (1) {
		int n, m;
		scanf("%d%d", &n, &m);
		if (!n && !m)
			break;
		int res = 0;
		vector<GraphEdge> e(m);
		for (int i = 0; i < m; i++){
			e[i].readinput();
			res += e[i].w;
		}
		sort(e.begin(), e.end());
		union_find uf;
		uf.init(n);
		int cnt = 0, mst = 0;
		for (int i = 0; cnt<n-1 && i < m; i++){
			if (uf.dstunion(e[i].x, e[i].y)){
				res -= e[i].w;
				cnt++;
				mst += e[i].w;
			}
		}
		
		printf("%d\n" , res); 
	} 

	
	return 0;
}
