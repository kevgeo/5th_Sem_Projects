#include <iostream>
#include <bits/stdc++.h>
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/opencv.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include <opencv2/objdetect/objdetect.hpp>

using namespace cv; 
using namespace std; 

int main()
{
	// Reading image
	Mat img = imread("1.jpg");

	// Displaying image
	imshow("Original Image",img);
	waitKey(0);

	// Converting to grayscale
	Mat img_gray;
	cvtColor(img,img_gray,CV_RGB2GRAY);

	// Displaying grayscale image
	imshow("Original Image",img_gray);
	waitKey(0);

	cout<<"Number of columns:"<< img_gray.cols<<endl;
	int cols = img_gray.cols;
	cout<<"Number of rows:"<< img_gray.rows<<endl;
	int rows = img_gray.rows;

	// Creating sobel operator in x direction
	int sobel_x[3][3] = {-1,0,1,-2,0,2,-1,0,1};
	for(int i=0; i<3; i++)
	{
		for(int j=0; j<3; j++)
			{ cout<<sobel_x[i][j]<<"  "; }

		cout<<endl;
	}

	// Creating sobel operator in y direction
	int sobel_y[3][3] = {1,2,1,0,0,0,-1,-2,-1};

	Mat gradient = img_gray.clone()	;
	imshow("grad",grad);
	waitKey(0);

	int rows_kernel = 3;
	int cols_kernel = 3;
	int temp = 0;
	//The below two indexes are for neighbourhood pixels
	int idx1=0;
	int idx2=0;
	// Correlation of sobel operator with image
	for(int i=0; i<cols; i++)
	{
		for(int j=0; j<rows; j++)
		{
			
			// When pixel is at top-left corner or along first column
			if( (i == 0 && j == 0) || ( i!=(rows-1) && j==0 ))
			{
					idx1=i; idx2=j;
					// Below two for loops are for accessing elements of kernel
					for(int f=rows_kernel/2; f<rows_kernel; f++)
					{
						for(int k=cols_kernel/2; k<cols_kernel; k++)
						{
							temp += sobel_x[f][k] * img_gray[idx1][idx2];
							idx2++;
						}
						idx1++;
						idx2=0;
					}

					gradient.at<i,j> = temp;
			}

			// When pixel is in first column 
			if( j == 0 )
			{

			}

			// When pixel is in last column
			if( j == cols)
			{

			}

			// When pixel is in first row
			if( i == 0)
			{

			}


			// When pixel is in last row
			if( i == rows )
			{

			}	


		}
	}

	return 0;

}